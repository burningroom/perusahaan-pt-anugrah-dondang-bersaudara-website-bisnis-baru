<?php

namespace App\Http\Controllers;

use App\Services\SoapService;
use App\Services\InaportnetService;
use Illuminate\Http\Request;
use Throwable;

class InaportnetController extends Controller
{
    public function handle(Request $request, SoapService $soap, InaportnetService $inaportnetService)
    {
        if ($request->isMethod('get') || $request->query->has('wsdl')) {
            return $this->serveWsdl($request);
        }

        $raw = $request->getContent();

        if (!is_string($raw) || trim($raw) === '') {
            $xml = $soap->fault('Client', 'Empty SOAP body');
            return response($xml, 200)->header('Content-Type', 'text/xml; charset=utf-8');
        }

        try {
            [$action, $payload] = $soap->parseIncoming($raw);
        } catch (Throwable $e) {
            $xml = $soap->fault('Client', 'Malformed SOAP XML');
            return response($xml, 200)->header('Content-Type', 'text/xml; charset=utf-8');
        }

        $extras = [];
        try {
            switch (strtolower($action)) {
                case 'entryperpanjangmasatambat':
                case 'entrypembatalanpkk':
                case 'entryalihagen':
                case 'entrypkk':
                    $extras = $inaportnetService->entryPKK($payload);
                    break;
                case 'entryppk':
                    $extras = [
                        // Echo back if present, otherwise put your own values
                        'nomorPpk' => $payload['nomorPpk'] ?? '',
                        'nomorRpkro' => $payload['nomorRpkRo'] ?? '',
                    ];
                    break;
                case 'entryrkbm':
                    $extras = [
                        'nomorRKBM' => $payload['nomorRKBM'] ?? '',
                        'portCode' => $payload['portCode'] ?? $payload['kodeMuatPelabuhan'] ?? '',
                    ];
                    break;
                case 'entryrpkro':
                case 'entryrpkrodetail':
                    $extras = [
                        'NomorRpkRo' => $payload['NomorRpkRo'] ?? '',
                    ];
                    break;
                case 'setspkpandu':
                    $extras = [
                        'NomorSPKPandu' => $payload['NomorSPKPandu'] ?? '',
                    ];
                    break;
                case 'entryspog':
                    $extras = [
                        'nomorPpkb' => $payload['nomorPkk'] ?? '',
                        'nomorSpk' => $payload['nomorSpk'] ?? '',
                        'nomorSpog' => $payload['nomorSpog'] ?? '',
                        'portCode' => $payload['portCode'] ?? '',
                    ];
                    break;
                case 'sendrealisasipandu':
                    $extras = [
                        'NomorSPKPandu' => $payload['NomorSpk'] ?? '',
                    ];
                    break;
                case 'sendrealisasitunda':
                case 'sendrealisasitambat':
                    $extras = [
                        'NomorPKK' => $payload['NomorPkk'] ?? '',
                    ];
                    break;
                case 'entrypindahkeluar':
                    $extras = [
                        'nomorPindahKeluar' => $payload['nomorPindahKeluar'] ?? '',
                    ];
                    break;
                case 'entryspb':
                    $extras = [
                        'nomorPkk' => $payload['nomorPkk'] ?? '',
                        'nomorSPB' => $payload['nomorSPB'] ?? '',
                        'portCode' => $payload['portCode'] ?? ''
                    ];
                    break;
                case 'sendrealisasi':
                case 'monitorpandu':
                    break;
                default:
                    // Unknown/unsupported operation name
                    $xml = $soap->makeResponse(
                        $action ?: 'Unknown',
                        [
                            'statusCode' => '05',
                            'statusMessage' => 'unknown function',
                        ]
                    );
                    return response($xml, 200)->header('Content-Type', 'text/xml; charset=utf-8');
            }
        } catch (Throwable $e) {
            $xml = $soap->makeResponse(
                $action ?: 'Unknown',
                [
                    'statusCode' => '02',
                    'statusMessage' => $e->getMessage(),
                ]
            );
            return response($xml, 200)->header('Content-Type', 'text/xml; charset=utf-8');
        }

        // Always include required fields
        $base = [
            'statusCode'    => '01',
            'statusMessage' => 'success',
        ];

        $xml = $soap->makeResponse($action, array_merge($extras, $base));

        return response($xml, 200)->header('Content-Type', 'text/xml; charset=utf-8');
    }

    private function serveWsdl(\Illuminate\Http\Request $request)
    {
        // Point to your WSDL file
        $path = resource_path('wsdl/inaportnet.wsdl'); // or inaportnet-compat.wsdl
        if (!is_file($path)) {
            abort(404, 'WSDL not found');
        }

        // Read and optionally patch the soap:address to this route
        $wsdl = file_get_contents($path);
        $endpointUrl = route('inaportnets'); // your POST/GET endpoint
        $wsdl = $this->patchSoapAddress($wsdl, $endpointUrl);

        // Serve INLINE (not as attachment) so the browser renders it
        return response($wsdl, 200, [
            'Content-Type'        => 'text/xml; charset=UTF-8',        // <- renders in browser
            'Content-Disposition' => 'inline; filename="inaportnet.wsdl"', // <- NOT attachment
            'X-Content-Type-Options' => 'nosniff', // optional safety
        ]);
    }

    private function patchSoapAddress(string $wsdlXml, string $endpoint): string
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        if (@$dom->loadXML($wsdlXml) === false) return $wsdlXml;

        $xp = new \DOMXPath($dom);
        $xp->registerNamespace('wsdl', 'http://schemas.xmlsoap.org/wsdl/');
        $xp->registerNamespace('soap', 'http://schemas.xmlsoap.org/wsdl/soap/');

        foreach ($xp->query('//wsdl:service/wsdl:port/soap:address') as $addr) {
            /** @var \DOMElement $addr */
            $addr->setAttribute('location', $endpoint);
        }
        return $dom->saveXML();
    }
}
