<?php

namespace App\Traits;

use RicorocksDigitalAgency\Soap\Facades\Soap;
use Illuminate\Support\Facades\Log;

trait InaportnetSoapTrait
{
    /**
     * Panggil SOAP action
     *
     * @param string $action Nama fungsi SOAP (misal: entryPKK, entryRKBM)
     * @param array $params Payload SOAP (array)
     * @return array
     */
    public function soapRequest(string $action, array $params): array
    {
        try {
            $response = Soap::to(config('inaportnet.wsdl'))
                ->withOptions([
                    'soap_version' => SOAP_1_1, // biasanya SOAP 1.1, tapi sesuaikan dengan WSDL
                    'trace' => true,
                ])
                ->call($action, $params);

            return [
                'success' => true,
                'data' => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error("SOAP Error [$action]: " . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
