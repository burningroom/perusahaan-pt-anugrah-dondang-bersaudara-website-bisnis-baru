<?php

namespace App\Http\Middleware;

use App\Models\SoapLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LogSoapTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // mark start time & generate correlation id
        $request->attributes->set('soap_logger_start', microtime(true));
        $id = (string) Str::uuid();
        $request->attributes->set('soap_log_id', $id);

        // raw body (XML) & misc meta
        $raw        = $request->getContent();
        $soapAction = $request->header('SOAPAction');
        $isWsdl     = $request->query->has('wsdl');

        // Attempt to detect the SOAP operation from the Body
        $operation  = $this->detectOperation($raw);
        if ($isWsdl) { $operation = 'WSDL'; }

        // truncate huge payloads to protect DB size
        $maxKb = (int) config('services.inaportnet.soap_logger_max_kb', 2048); // 2MB default
        $reqXml = $this->truncate($raw, $maxKb * 1024);

        SoapLog::create([
            'id'          => $id,
            'path'        => $request->path(),
            'method'      => $request->method(),
            'ip'          => $request->ip(),
            'user_agent'  => substr((string) $request->userAgent(), 0, 255),
            'soap_action' => $soapAction,
            'operation'   => $operation,
            'is_wsdl'     => $isWsdl,
            'headers'     => $this->filterHeaders($request->headers->all()),
            'request_xml' => $reqXml,
        ]);

        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        $id = $request->attributes->get('soap_log_id');
        if (!$id) return;

        $start = (float) ($request->attributes->get('soap_logger_start') ?? microtime(true));
        $tookMs = (int) round((microtime(true) - $start) * 1000);

        $respXml = null;
        if (method_exists($response, 'getContent')) {
            $maxKb  = (int) config('services.inaportnet.soap_logger_max_kb', 2048);
            $respXml = $this->truncate($response->getContent(), $maxKb * 1024);
        }

        SoapLog::where('id', $id)->update([
            'status_code'  => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
            'took_ms'      => $tookMs,
            'response_xml' => $respXml,
        ]);
    }

    private function detectOperation(?string $xml): ?string
    {
        if (!$xml) return null;

        try {
            $doc = new \DOMDocument();
            $doc->loadXML($xml, LIBXML_NOERROR | LIBXML_NOWARNING);
            $xp  = new \DOMXPath($doc);
            // Support default SOAP 1.1 namespace
            $xp->registerNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
            // Find first element inside Body
            $nodes = $xp->query('/*[local-name()="Envelope"]/*[local-name()="Body"]/*[1]');
            if ($nodes && $nodes->length) {
                return $nodes->item(0)->localName; // e.g. entryPKK / SetSpkPandu
            }
        } catch (\Throwable $e) {
            // ignore parse errors
        }

        return null;
    }

    private function filterHeaders(array $headers): array
    {
        $masked = [];
        foreach ($headers as $k => $v) {
            $key = strtolower($k);
            if (in_array($key, ['authorization', 'cookie', 'x-api-key'])) {
                $masked[$k] = ['***'];
            } else {
                $masked[$k] = $v;
            }
        }
        return $masked;
    }

    private function truncate(?string $s, int $maxBytes): ?string
    {
        if ($s === null) return null;
        if (strlen($s) <= $maxBytes) return $s;
        return substr($s, 0, $maxBytes) . "\n<!-- truncated -->";
    }
}
