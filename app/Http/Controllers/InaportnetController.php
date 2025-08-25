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
        // Allow a simple GET probe / ?wsdl passthrough if you want
        if ($request->isMethod('get') && !$request->has('wsdl')) {
            return response('Inaportnet SOAP endpoint is up', 200)
                ->header('Content-Type', 'text/plain; charset=utf-8');
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
}
