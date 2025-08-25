<?php

namespace App\Services;

use App\Models\Company;
use App\Models\RequestArrival;
use App\Traits\InaportnetSoapTrait;
use Carbon\Carbon;

class InaportnetService
{
    use InaportnetSoapTrait;

    public function entryPKK(array $data): array
    {
        try {
            $nomor_pkk = $data['nomorPKK'];
            $portCode = $data['portCode'] ?? $data['kodeMuatPelabuhan'] ?? null;
            if ($nomor_pkk === null)
                throw new \Exception("Nomor PKK tidak boleh kosong!");

            $request_arrival = RequestArrival::where("nomor_pkk", $nomor_pkk)->first();
            if (!$request_arrival)
                throw new \Exception("Data Pengajuan Kedatangan Kapal tidak ditemukan");

            $pkk = $request_arrival->pkk()->firstOrCreate(
                [
                    'pkk_number' => $nomor_pkk ?? null,
                ],
                [
                    'route_type' => $data['jenisTrayek'] ?? null,
                    'route_number' => $data['nomorTrayek'] ?? null,
                    'bm_status' => $data['statusBM'] ?? null,
                    'total_unload' => (int)($data['jumlahBongkar'] ?? 0),
                    'total_load' => (int)($data['jumlahMuat'] ?? 0),
                    'port_code' => $portCode,
                    'item_type' => $data['jenisBarang'] ?? null,
                    'status' => 'sukses',
                    'description' => 'Inaportnet : Sukses',
                    'tanggalEta' => empty($data['tanggalEta']) ? null : Carbon::parse($data['tanggalEta'])->toDateTimeString(),
                    'tanggalEtd' => empty($data['tanggalEtd']) ? null : Carbon::parse($data['tanggalEtd'])->toDateTimeString(),
                    'pmku_pandu_number' => $data['noPmkuPandu'] ?? null,
                    'npwp_pandu_number' => $data['noNpwpPandu'] ?? null,
                    'pandu_name' => $data['namaPandu'] ?? null,
                    'mmsi' => $data['mmsi'] ?? null,
                    'status_window' => $data['statusWindow'] ?? null,
                    'status_but' => $data['statusBut'] ?? 'N'
                ]);

            if (!$pkk)
                throw new \Exception("Gagal menyimpan data PKK!");

            $spk_pandu = $pkk->spkPandu()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id
                ],
                [
                    'nomor_pkk' => $nomor_pkk,
                    'status' => 'permintaan',
                ]
            );

            if (!$spk_pandu)
                throw new \Exception("Gagal menyimpan data SPK Pandu!");

            $port = $pkk->port()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'port_code' => $portCode,
                    'port_name' => $data['muatPelabuhan'] ?? null,
                    'port_origin_code' => $data['kodeAsalPelabuhan'] ?? null,
                    'origin_port' => $data['asalPelabuhan'] ?? null,
                    'load_port_code' => $data['kodeMuatPelabuhan'] ?? null,
                    'load_port_name' => $data['muatPelabuhan'] ?? null,
                    'destination_port_code' => $data['kodeTujuanPelabuhan'] ?? null,
                    'destination_port_name' => $data['tujuanPelabuhan'] ?? null,
                    'final_destination_port_code' => $data['kodeTujuanAkhirPelabuhan'] ?? null,
                    'final_destination_port_name' => $data['tujuanAkhirPelabuhan'] ?? null,
                ]
            );

            if (!$port) throw new \Exception("Gagal menyimpan data Port!");

            $terminal = $pkk->terminal()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'dock_code' => $data['kodeDermaga'] ?? null,
                    'dock_name' => $data['dermagaNama'] ?? null,
                ]
            );

            if (!$terminal) throw new \Exception("Gagal menyimpan data Terminal!");

            $company_id = null;
            $nama_perusahaan = $data['perusahaanNama'];
            $npwp_perusahaan = $data['npwp'];
            if ($nama_perusahaan && $npwp_perusahaan) {
                $company_id = Company::where('name', 'like', "%$nama_perusahaan%")
                    ->where('npwp', 'like', "%$npwp_perusahaan%")
                    ->first()?->id;
            }

            $principal = $pkk->principal()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'company_id' => $company_id,
                    'company_name' => $nama_perusahaan ?? null,
                    'npwp' => $npwp_perusahaan ?? null,
                    'principal_npwp' => empty($data['npwpPrincipal']) ? null : $data['npwpPrincipal'],
                    'principal_name' => empty($data['namaPrincipal']) ? null : $data['namaPrincipal'],
                    'principal_country' => $data['negaraPrincipal'] ?? null,
                    'but_status' => $data['statusBut'] ?? null,
                ]
            );

            if (!$principal) throw new \Exception("Gagal menyimpan data Principal!");

            $ship = $pkk->ship()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'registration_number' => $data['tandaPendaftaranKapal'] ?? null,
                    'name' => $nama_kapal ?? null,
                    'captain_name' => $data['nahkoda'] ?? null,
                    'drt' => empty($data['drt']) ? null : (int)$data['drt'],
                    'grt' => empty($data['grt']) ? null : (int)$data['grt'],
                    'loa' => empty($data['loa']) ? null : (float)$data['loa'],
                    'ship_type' => $data['jenisKapal'] ?? null,
                    'year_build' => empty($data['tahunPembuatan']) ? null : (int)$data['tahunPembuatan'],
                    'width' => empty($data['lebarKapal']) ? null : (float)$data['lebarKapal'],
                    'max_draft' => empty($data['drMax']) ? null : (float)$data['drMax'],
                    'front_draft' => empty($data['drDepan']) ? null : (float)$data['drDepan'],
                    'rear_draft' => empty($data['drBelakang']) ? null : (float)$data['drBelakang'],
                    'midship_draft' => empty($data['drTengah']) ? null : (float)$data['drTengah'],
                    'call_sign' => $data['callSign'] ?? null,
                    'flag' => $data['bendera'] ?? null,
                    'imo_number' => empty($data['imoNumber']) ? null : $data['imoNumber'],
                ]
            );

            if (!$ship) throw new \Exception("Gagal menyimpan data Kapal!");

            $cargo = $pkk->cargo()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'mixed_cargo_unload' => empty($data['cargoBarangCampurBongkar']) ? null : $data['cargoBarangCampurBongkar'],
                    'mixed_cargo_load' => empty($data['cargoBarangCampurMuat']) ? null : $data['cargoBarangCampurMuat'],
                    'dangerous_good_cargo_unload' => empty($data['cargoBarangBerbahayaBongkar']) ? null : $data['cargoBarangBerbahayaBongkar'],
                    'dangerous_good_cargo_load' => empty($data['cargoBarangBerbahayaMuat']) ? null : $data['cargoBarangBerbahayaMuat'],
                    'unload_amount' => $data['jumlahBongkar'] ?? null,
                ]
            );

            if (!$cargo) throw new \Exception("Gagal menyimpan data kargo!");

            $container = $pkk->container()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'load_20_filled' => empty($data['containerMuatIsi20']) ? null : $data['containerMuatIsi20'],
                    'load_40_filled' => empty($data['containerMuatIsi40']) ? null : $data['containerMuatIsi40'],
                    'unload_20_filled' => empty($data['containerBongkarIsi20']) ? null : $data['containerBongkarIsi20'],
                    'unload_40_filled' => empty($data['containerBongkarIsi40']) ? null : $data['containerBongkarIsi40'],

                ]
            );

            if (!$container) throw new \Exception("Gagal menyimpan data Kontainer!");

            return [
                'nomorPKK' => $nomor_pkk ?? '',
                'portCode' => $portCode ?? '',
            ];
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getEntryPKK(string $nomorPKK): array
    {
        if (config('app.env') == 'production')
            return $this->soapRequest('getEntryPKK', [
                'user' => config('inaportnet.user'),
                'password' => config('inaportnet.password'),
                'nomorPKK' => $nomorPKK
            ]);

        $xml = '<ns2:getEntryPKKResponse xmlns:ns2="http://src/">
                    <return>
                        <portCode>IDSUB</portCode>
                        <statusCode>01</statusCode>
                        <statusMessage>Success</statusMessage>
                        <nomorPKK>PKK.DN.IDMAK.1604.000002</nomorPKK>
                        <nomorPPK>PPK.SUB.1602.00001</nomorPPK>
                        <perusahaanNama>PT. PELAYARAN ALKAN ABADI</perusahaanNama>
                        <tandaPendaftaranKapal>2002_Ka_No._2996/L</tandaPendaftaranKapal>
                        <kapalNama>Tanimbar Sehati ex Tanimbar Sejati</kapalNama>
                        <nahkoda>YOHANES YAMLEAN</nahkoda>
                        <drt>16000</drt>
                        <grt>1007</grt>
                        <loa>57.18</loa>
                        <jenisTrayek>02</jenisTrayek>
                        <bendera>ID</bendera>
                        <callSign>YGWI</callSign>
                        <imoNumber></imoNumber>
                        <tanggalEta>2016-04-15 13:05:00</tanggalEta>
                        <tanggalEtd>2016-04-18 09:10:00</tanggalEtd>
                        <kodeAsalPelabuhan>IDENE</kodeAsalPelabuhan>
                        <asalPelabuhan>ENDE, FLORES</asalPelabuhan>
                        <kodeTujuanPelabuhan>IDLWE</kodeTujuanPelabuhan>
                        <tujuanPelabuhan>LEWOLEBA </tujuanPelabuhan>
                        <kodeDermaga>IDSUB.T01.B01</kodeDermaga>
                        <dermagaNama>HASANUDDIN - MULTIPURPOSE I</dermagaNama>
                        <penumpangNaikTurunBongkar></penumpangNaikTurunBongkar>
                        <penumpangNaikTurunMuat></penumpangNaikTurunMuat>
                        <jenisBarang>Bag Cargo</jenisBarang>
                        <containerBongkarIsi40 xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerBongkarIsi20 xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerBongkarIsi40Empty xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerBongkarIsi20Empty xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerMuatIsi40 xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerMuatIsi20 xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerMuatIsi40Empty xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <containerMuatIsi20Empty xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <cargoBarangCampurBongkar></cargoBarangCampurBongkar>
                        <cargoBarangCampurMuat></cargoBarangCampurMuat>
                        <cargoBarangKarungBongkar></cargoBarangKarungBongkar>
                        <cargoBarangKarungMuat>1600Ton</cargoBarangKarungMuat>
                        <cargoBarangCurahBongkar></cargoBarangCurahBongkar>
                        <cargoBarangCurahMuat></cargoBarangCurahMuat>
                        <cargoBarangCairBongkar></cargoBarangCairBongkar>
                        <cargoBarangCairMuat></cargoBarangCairMuat>
                        <cargoBarangBerbahayaBongkar></cargoBarangBerbahayaBongkar>
                        <cargoBarangBerbahayaMuat></cargoBarangBerbahayaMuat>
                        <jenisBarangLain></jenisBarangLain>
                        <jenisBarangLainBongkar></jenisBarangLainBongkar>
                        <jenisBarangLainMuat></jenisBarangLainMuat>
                        <jumlahBongkar>0</jumlahBongkar>
                        <jumlahMuat>1600</jumlahMuat>
                        <hewanNaikTurunBongkar></hewanNaikTurunBongkar>
                        <hewanNaikTurunMuat></hewanNaikTurunMuat>
                        <portCode>IDMAK</portCode>
                        <sts>2</sts>
                        <npwp>01.673.218.2-801.002</npwp>
                        <kodeMuatPelabuhan>IDGRE</kodeMuatPelabuhan>
                        <muatPelabuhan>GRESIK</muatPelabuhan>
                        <kodeTujuanAkhirPelabuhan>IDMAK</kodeTujuanAkhirPelabuhan>
                        <tujuanAkhirPelabuhan>MAKASSAR</tujuanAkhirPelabuhan>
                        <jenisKapal>1.7.1</jenisKapal>
                        <tahunPembuatan>2002</tahunPembuatan>
                        <lebarKapal>12</lebarKapal>
                        <drMax>4.8</drMax>
                        <drDepan>2.1</drDepan>
                        <drBelakang>3.2</drBelakang>
                        <drTengah xsi:nil="true" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" />
                        <noinmarsat></noinmarsat>
                        <nomorTrayek>AL.302/59/10/157/16</nomorTrayek>
                        <statusBM>Y</statusBM>
                        <npwpPrincipal></npwpPrincipal>
                        <namaPrincipal></namaPrincipal>
                        <statusBut>N</statusBut>
                        <noPmkuPandu>PMKU.IDJKT.0621.000025</noPmkuPandu>
                        <noNpwpPandu>ZAMRUD</noNpwpPandu>
                        <namaPandu>PT Jasa Aramada Indonesia</namaPandu>
                        <mmsi>123</mmsi>
                        <statusWindow>N</statusWindow>
                    </return>
                </ns2:getEntryPKKResponse>';
        $xmlObj = simplexml_load_string($xml);
        $response = json_decode(json_encode($xmlObj), true);

        return [
            'success' => true,
            'data' => $response['return'],
        ];
    }

    public function entryRKBM(array $data): array
    {
        return $this->soapRequest('entryRKBM', ['req' => $data]);
    }

    public function entryRpkro(array $data): array
    {
        if (config('app.env') == 'production') {
            $payload = [
                'user' => config('inaportnet.user'),
                'password' => config('inaportnet.password'),
            ];
            $payload = array_merge($payload, $data);
            return $this->soapRequest('entryRpkro', $payload);
        }
        $xml = '<ns2:entryRpkroResponse xmlns:ns2="http://src/">
                    <entryRpkroResult>
                        <return>
                            <statusCode>01</statusCode>
                            <statusMessage>Success</statusMessage>
                            <NomorRpkRo>RPKRO.SUB.1602.00001</NomorRpkRo>
                        </return>
                    </entryRpkroResult>
                </ns2:entryRpkroResponse>';
        $xmlObj = simplexml_load_string($xml);
        $response = json_decode(json_encode($xmlObj), true);

        return [
            'success' => true,
            'data' => $response['entryRpkroResult']['return'],
        ];
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
