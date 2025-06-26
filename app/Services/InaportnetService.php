<?php

namespace App\Services;

use App\Traits\InaportnetSoapTrait;

class InaportnetService
{
    use InaportnetSoapTrait;

    public function entryPKK(array $data): array
    {
        return $this->soapRequest('entryPKK', ['req' => $data]);
    }

    public function getEntryPKK(string $nomorPKK): array
    {
        return $this->soapRequest('getEntryPKK', [
            'user' => config('inaportnet.user'),
            'password' => config('inaportnet.password'),
            'nomorPKK' => $nomorPKK
        ]);
    }

    public function entryRKBM(array $data): array
    {
        return $this->soapRequest('entryRKBM', ['req' => $data]);
    }

    public function entryRpkro(array $data): array
    {
        return $this->soapRequest('entryRpkro', ['req' => $data]);
    }

    public function entryPPK(array $data): array
    {
        return $this->soapRequest('entryPPK', ['req' => $data]);
    }

    public function setSpkPandu(array $data): array
    {
        return $this->soapRequest('setSpkPandu', ['req' => $data]);
    }

    public function entrySPOG(array $data): array
    {
        return $this->soapRequest('entrySPOG', ['req' => $data]);
    }

    public function sendRealisasiPandu(array $data): array
    {
        return $this->soapRequest('sendRealisasiPandu', ['req' => $data]);
    }

    public function sendRealisasiTunda(array $data): array
    {
        return $this->soapRequest('sendRealisasiTunda', ['req' => $data]);
    }

    public function sendRealisasiTambat(array $data): array
    {
        return $this->soapRequest('sendRealisasiTambat', ['req' => $data]);
    }

    public function entrySPM(array $data): array
    {
        return $this->soapRequest('entrySPM', ['req' => $data]);
    }

    public function entryNota(array $data): array
    {
        return $this->soapRequest('entryNota', ['req' => $data]);
    }

    public function entryPraNota(array $data): array
    {
        return $this->soapRequest('entryPraNota', ['req' => $data]);
    }

    public function getApprovalPraNota(array $data): array
    {
        return $this->soapRequest('getApprovalPraNota', ['req' => $data]);
    }

    public function sendApprovalNota(array $data): array
    {
        return $this->soapRequest('sendApprovalNota', ['req' => $data]);
    }

    public function sendSpkPandu(array $data): array
    {
        return $this->soapRequest('sendSpkPandu', ['req' => $data]);
    }

    public function getSPKBelumRealisasi(array $data): array
    {
        return $this->soapRequest('getSPKBelumRealisasi', ['req' => $data]);
    }

    public function entryEpb(array $data): array
    {
        return $this->soapRequest('entryEpb', ['req' => $data]);
    }

    public function entryPpkb(array $data): array
    {
        return $this->soapRequest('entryPpkb', ['req' => $data]);
    }

    public function sendPpkb(array $data): array
    {
        return $this->soapRequest('sendPpkb', ['req' => $data]);
    }

    public function sendRealisasi(array $data): array
    {
        return $this->soapRequest('sendRealisasi', ['req' => $data]);
    }
}
