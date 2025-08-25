<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use SimpleXMLElement;

class SoapService
{
    private string $soapEnvNs = 'http://schemas.xmlsoap.org/soap/envelope/';
    private string $respNs    = 'http://src/';   // <-- change if your WSDL uses a different targetNamespace
    private string $respPref  = 'ns2';           // response namespace prefix
    private string $soapPref  = 'S';             // envelope namespace prefix

    /**
     * Parse incoming SOAP XML, return [actionName, payloadAssoc].
     */
    public function parseIncoming(string $xml): array
    {
        $sx = @simplexml_load_string($xml);
        if ($sx === false) {
            throw new \RuntimeException('Invalid XML');
        }

        // Find Body/*[1] regardless of namespace
        $nodes = $sx->xpath('/*[local-name()="Envelope"]/*[local-name()="Body"]/*[1]');
        if (!$nodes || !isset($nodes[0])) {
            throw new \RuntimeException('SOAP Body not found');
        }

        /** @var SimpleXMLElement $op */
        $op = $nodes[0];
        $action = $op->getName(); // local name (no prefix)
        $payload = $this->xmlToArray($op);

        return [$action, $payload];
    }

    /**
     * Build a SOAP 1.1 response:
     * <S:Envelope><S:Body><ns2:{Action}Response><return>...</return></ns2:...></S:Body></S:Envelope>
     */
    public function makeResponse(string $action, array $returnFields): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $envelope = $doc->createElementNS($this->soapEnvNs, "{$this->soapPref}:Envelope");
        $body     = $doc->createElementNS($this->soapEnvNs, "{$this->soapPref}:Body");

        $respName = $action . 'Response';
        $resp     = $doc->createElementNS($this->respNs, "{$this->respPref}:{$respName}");
        $ret      = $doc->createElement('return');

        $this->arrayToElements($doc, $ret, $returnFields);

        $resp->appendChild($ret);
        $body->appendChild($resp);
        $envelope->appendChild($body);
        $doc->appendChild($envelope);

        return $doc->saveXML();
    }

    /**
     * Build a SOAP 1.1 Fault (useful for malformed requests).
     */
    public function fault(string $faultCode, string $faultString): string
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $envelope = $doc->createElementNS($this->soapEnvNs, "{$this->soapPref}:Envelope");
        $body     = $doc->createElementNS($this->soapEnvNs, "{$this->soapPref}:Body");

        $fault    = $doc->createElement('Fault');
        $fault->appendChild($doc->createElement('faultcode', $faultCode));
        $fault->appendChild($doc->createElement('faultstring', $faultString));

        $body->appendChild($fault);
        $envelope->appendChild($body);
        $doc->appendChild($envelope);

        return $doc->saveXML();
    }

    /**
     * Convert SimpleXML element subtree into associative array (recursively).
     */
    private function xmlToArray(SimpleXMLElement $element): array
    {
        $out = [];
        foreach ($element->children() as $child) {
            $name = $child->getName();
            if ($child->count() > 0) {
                $out[$name] = $this->xmlToArray($child);
            } else {
                $out[$name] = trim((string) $child);
            }
        }
        return $out;
    }

    /**
     * Append array(key => value) into DOM as children (recursively).
     */
    private function arrayToElements(DOMDocument $doc, DOMElement $parent, array $data): void
    {
        foreach ($data as $key => $value) {
            // If numeric key, skip/rename to generic item
            $tag = is_string($key) && $key !== '' ? $key : 'item';

            if (is_array($value)) {
                $node = $doc->createElement($tag);
                $this->arrayToElements($doc, $node, $value);
                $parent->appendChild($node);
            } else {
                $parent->appendChild($doc->createElement($tag, (string) $value));
            }
        }
    }
}
