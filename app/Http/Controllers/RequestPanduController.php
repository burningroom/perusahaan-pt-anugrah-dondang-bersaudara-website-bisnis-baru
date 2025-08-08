<?php

namespace App\Http\Controllers;

use App\Filament\Resources\RequestPanduResource;
use App\Models\Company;
use App\Models\RequestArrival;
use App\Models\User;
use App\Services\InaportnetService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RequestPanduController extends BaseController
{
    protected InaportnetService $inaportnetService;

    public function __construct(InaportnetService $inaportnetService)
    {
        $this->inaportnetService = $inaportnetService;
    }

    private function getRequestArrivalStatus($status): string
    {
        switch ($status) {
            case 'setuju':
                return 'Pengajuan RPKRO';
            case 'proses':
                return 'Pemintaan di Proses';
            default:
                return 'Permintaan';
        }
    }

    private function getSPKPanduStatus($status): string
    {
        switch ($status) {
            case 'selesai':
                return 'SPK Pandu Selesai';
            case 'setuju':
                return 'SPK Pandu Disetujui';
            default:
                return 'Pengajuan SPK Pandu';
        }
    }

    private function getRPKROStatus($status): string
    {
        if ($status == 'setuju')
            return 'Pengajuan SPK Pandu';
        return 'Pengajuan RPKRO';
    }

    public function indexAgent(): JsonResponse
    {
        $user = Auth::user();
        $user = User::find($user?->id);

        if (!$user)
            return $this->sendError("Maaf, Data User $this->notfound_msg");

        $request_arrivals = $user->requestArrivals()->latest()->get();

        $data = [];
        foreach ($request_arrivals as $request_arrival) {
            $pkk = $request_arrival->pkk;
            if ($pkk->spkPandu) {
                $status = $this->getSPKPanduStatus($pkk->spkPandu->status);
            } elseif ($pkk->rpkro) {
                $status = $this->getRPKROStatus($pkk->rpkro->status);
            } else {
                $status = $this->getRequestArrivalStatus($request_arrival->status);
            }

            $data[] = [
                'id' => $request_arrival->id,
                'nomor_pkk' => $request_arrival->nomor_pkk,
                'nama_kapal' => $request_arrival->vessel_tb?->name,
                'waktu_pengolongan' => $request_arrival->waktu_pengolongan?->toDateTimeString(),
                'jenis_pengolongan' => $request_arrival->jenis_pengolongan,
                'lokasi_awal' => $request_arrival->lokasi_awal,
                'lokasi_akhir' => $request_arrival->lokasi_akhir,
                'status' => $status,
            ];
        }

        return $this->sendResponse($data, "Data Pengajuan Kedatangan Kapal $this->found_msg");
    }

    public function indexPandu(): JsonResponse
    {
        $request_arrivals = RequestArrival::latest()->get();

        $data = [];
        foreach ($request_arrivals as $request_arrival) {
            $pkk = $request_arrival->pkk;
            if ($pkk->spkPandu) {
                $status = $this->getSPKPanduStatus($pkk->spkPandu->status);
            } elseif ($pkk->rpkro) {
                $status = $this->getRPKROStatus($pkk->rpkro->status);
            } else {
                $status = $this->getRequestArrivalStatus($request_arrival->status);
            }

            $data[] = [
                'id' => $request_arrival->id,
                'nomor_pkk' => $request_arrival->nomor_pkk,
                'nama_kapal' => $request_arrival->vessel_tb?->name,
                'waktu_pengolongan' => $request_arrival->waktu_pengolongan?->toDateTimeString(),
                'jenis_pengolongan' => $request_arrival->jenis_pengolongan,
                'status' => $status,
            ];
        }

        return $this->sendResponse($data, "Data Pengajuan Kedatangan Kapal $this->found_msg");
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $user = User::find($user->id);

            $validator = Validator::make($request->all(), RequestArrival::validator());
            if ($validator->fails())
                return $this->sendError('Input tidak sesuai dengan ketentuan.', $validator->errors()->toArray(), 422);

            $tanggal = $request->input('tanggal');
            $waktu = $request->input('waktu');
            $vessel_tb = $request->input('vessel_tb');
            $vessel_bg = $request->input('vessel_bg');
            $nomor_pkk = $request->input('nomor_pkk');
            $rkbm_loading_number = $request->input('rkbm_loading_number');
            $rkbm_unloading_number = $request->input('rkbm_unloading_number');
            $loading_type = $request->input('loading_type');
            $loading = $request->input('loading');
            $jenis_pengolongan = $request->input('jenis_pengolongan');
            $lokasi_awal = $request->input('lokasi_awal');
            $lokasi_akhir = $request->input('lokasi_akhir');

            $user_id = $user->id;
            $dateTime = Carbon::createFromFormat('Y-m-d H:i', "$tanggal $waktu")->toDateTimeString();

            $vessel_tugboat = $user->vesselMasters()
                ->where('id', $vessel_tb)
                ->where('type', 'TB')
                ->first();

            if (!$vessel_tugboat)
                return $this->sendError("Data kapal tugboat $this->notfound_msg");

            $vessel_tongkang = $user->vesselMasters()
                ->where('id', $vessel_bg)
                ->whereIn('type', ['BG', 'LCT'])
                ->first();

            if (!$vessel_tongkang)
                return $this->sendError("Data kapal tongkang $this->notfound_msg");

            $responseGetEntryPKK = $this->inaportnetService->getEntryPKK($nomor_pkk);
            if (!$responseGetEntryPKK['success'])
                throw new \Exception($responseGetEntryPKK['message']);
            if ($responseGetEntryPKK['data']['statusCode'] != '01')
                throw new \Exception($responseGetEntryPKK['data']['statusMessage']);

            $getEntryPKK = $responseGetEntryPKK['data'];

            $nama_kapal = $getEntryPKK['kapalNama'] ?? null;

            $request_arrival = RequestArrival::updateOrCreate(
                [
                    'nomor_pkk' => $nomor_pkk,
                ],
                [
                    'user_id' => $user_id,
                    'vessel_tb' => $vessel_tb,
                    'vessel_bg' => $vessel_bg,
                    'rkbm_loading_number' => $rkbm_loading_number,
                    'rkbm_unloading_number' => $rkbm_unloading_number,
                    'loading_type' => $loading_type,
                    'loading' => $loading,
                    'waktu_pengolongan' => $dateTime,
                    'jenis_pengolongan' => $jenis_pengolongan,
                    'lokasi_awal' => $lokasi_awal,
                    'lokasi_akhir' => $lokasi_akhir,
                    'status' => 'permintaan',
                ]);
            if (!$request_arrival) throw new \Exception("Gagal menyimpan data request arrival!");

            if ($vessel_tb) {
                $request_arrival->vessel_requests()->updateOrCreate([
                    'request_arrival_id' => $request_arrival->id,
                    'vessel_master_id' => $vessel_tugboat->id
                ]);
            }

            if ($vessel_bg) {
                $request_arrival->vessel_requests()->updateOrCreate([
                    'request_arrival_id' => $request_arrival->id,
                    'vessel_master_id' => $vessel_tongkang->id,
                ]);
            }

            $pkk = $request_arrival->pkk()->firstOrCreate(
                [
                    'pkk_number' => $nomor_pkk,
                ],
                [
                    'user_id' => $user_id,
                    'route_type' => $getEntryPKK['jenisTrayek'] ?? null,
                    'route_number' => $getEntryPKK['nomorTrayek'] ?? null,
                    'bm_status' => $getEntryPKK['statusBM'] ?? null,
                    'total_unload' => (int)($getEntryPKK['jumlahBongkar'] ?? 0),
                    'total_load' => (int)($getEntryPKK['jumlahMuat'] ?? 0),
                    'port_code' => $getEntryPKK['portCode'][0] ?? null,
                    'item_type' => $getEntryPKK['jenisBarang'] ?? null,
                    'status' => 'sukses',
                    'description' => 'Inaportnet : Sukses',
                    'tanggalEta' => empty($getEntryPKK['tanggalEta']) ? null : Carbon::parse($getEntryPKK['tanggalEta'])->toDateTimeString(),
                    'tanggalEtd' => empty($getEntryPKK['tanggalEtd']) ? null : Carbon::parse($getEntryPKK['tanggalEtd'])->toDateTimeString(),
                    'pmku_pandu_number' => $getEntryPKK['noPmkuPandu'] ?? null,
                    'npwp_pandu_number' => $getEntryPKK['noNpwpPandu'] ?? null,
                    'pandu_name' => $getEntryPKK['namaPandu'] ?? null,
                    'mmsi' => $getEntryPKK['mmsi'] ?? null,
                    'status_window' => $getEntryPKK['statusWindow'] ?? null,
                    'status_but' => $getEntryPKK['statusBut'] ?? 'N'
                ]);

            if (!$pkk)
                throw new \Exception("Gagal menyimpan data PKK!");

            $spk_pandu = $pkk->spkPandu()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                    'agent_id' => $user_id,
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
                    'port_code' => $getEntryPKK['portCode'][1] ?? null,
                    'port_name' => $getEntryPKK['muatPelabuhan'] ?? null,
                    'port_origin_code' => $getEntryPKK['kodeAsalPelabuhan'] ?? null,
                    'origin_port' => $getEntryPKK['asalPelabuhan'] ?? null,
                    'load_port_code' => $getEntryPKK['kodeMuatPelabuhan'] ?? null,
                    'load_port_name' => $getEntryPKK['muatPelabuhan'] ?? null,
                    'destination_port_code' => $getEntryPKK['kodeTujuanPelabuhan'] ?? null,
                    'destination_port_name' => $getEntryPKK['tujuanPelabuhan'] ?? null,
                    'final_destination_port_code' => $getEntryPKK['kodeTujuanAkhirPelabuhan'] ?? null,
                    'final_destination_port_name' => $getEntryPKK['tujuanAkhirPelabuhan'] ?? null,
                ]
            );

            if (!$port) throw new \Exception("Gagal menyimpan data Port!");

            $terminal = $pkk->terminal()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'dock_code' => $getEntryPKK['kodeDermaga'] ?? null,
                    'dock_name' => $getEntryPKK['dermagaNama'] ?? null,
                ]
            );

            if (!$terminal) throw new \Exception("Gagal menyimpan data Terminal!");

            $company_id = null;
            $nama_perusahaan = $getEntryPKK['perusahaanNama'];
            $npwp_perusahaan = $getEntryPKK['npwp'];
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
                    'principal_npwp' => empty($getEntryPKK['npwpPrincipal']) ? null : $getEntryPKK['npwpPrincipal'],
                    'principal_name' => empty($getEntryPKK['namaPrincipal']) ? null : $getEntryPKK['namaPrincipal'],
                    'principal_country' => $getEntryPKK['negaraPrincipal'] ?? null,
                    'but_status' => $getEntryPKK['statusBut'] ?? null,
                ]
            );

            if (!$principal) throw new \Exception("Gagal menyimpan data Principal!");

            $ship = $pkk->ship()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'registration_number' => $getEntryPKK['tandaPendaftaranKapal'] ?? null,
                    'name' => $nama_kapal ?? null,
                    'captain_name' => $getEntryPKK['nahkoda'] ?? null,
                    'drt' => empty($getEntryPKK['drt']) ? null : (int)$getEntryPKK['drt'],
                    'grt' => empty($getEntryPKK['grt']) ? null : (int)$getEntryPKK['grt'],
                    'loa' => empty($getEntryPKK['loa']) ? null : (float)$getEntryPKK['loa'],
                    'ship_type' => $getEntryPKK['jenisKapal'] ?? null,
                    'year_build' => empty($getEntryPKK['tahunPembuatan']) ? null : (int)$getEntryPKK['tahunPembuatan'],
                    'width' => empty($getEntryPKK['lebarKapal']) ? null : (float)$getEntryPKK['lebarKapal'],
                    'max_draft' => empty($getEntryPKK['drMax']) ? null : (float)$getEntryPKK['drMax'],
                    'front_draft' => empty($getEntryPKK['drDepan']) ? null : (float)$getEntryPKK['drDepan'],
                    'rear_draft' => empty($getEntryPKK['drBelakang']) ? null : (float)$getEntryPKK['drBelakang'],
                    'midship_draft' => empty($getEntryPKK['drTengah']) ? null : (float)$getEntryPKK['drTengah'],
                    'call_sign' => $getEntryPKK['callSign'] ?? null,
                    'flag' => $getEntryPKK['bendera'] ?? null,
                    'imo_number' => empty($getEntryPKK['imoNumber']) ? null : $getEntryPKK['imoNumber'],
                ]
            );

            if (!$ship) throw new \Exception("Gagal menyimpan data Kapal!");

            $cargo = $pkk->cargo()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'mixed_cargo_unload' => empty($getEntryPKK['cargoBarangCampurBongkar']) ? null : $getEntryPKK['cargoBarangCampurBongkar'],
                    'mixed_cargo_load' => empty($getEntryPKK['cargoBarangCampurMuat']) ? null : $getEntryPKK['cargoBarangCampurMuat'],
                    'dangerous_good_cargo_unload' => empty($getEntryPKK['cargoBarangBerbahayaBongkar']) ? null : $getEntryPKK['cargoBarangBerbahayaBongkar'],
                    'dangerous_good_cargo_load' => empty($getEntryPKK['cargoBarangBerbahayaMuat']) ? null : $getEntryPKK['cargoBarangBerbahayaMuat'],
                    'unload_amount' => $getEntryPKK['jumlahBongkar'] ?? null,
                ]
            );

            if (!$cargo) throw new \Exception("Gagal menyimpan data kargo!");

            $container = $pkk->container()->updateOrCreate(
                [
                    'pkk_id' => $pkk->id,
                ],
                [
                    'load_20_filled' => empty($getEntryPKK['containerMuatIsi20']) ? null : $getEntryPKK['containerMuatIsi20'],
                    'load_40_filled' => empty($getEntryPKK['containerMuatIsi40']) ? null : $getEntryPKK['containerMuatIsi40'],
                    'unload_20_filled' => empty($getEntryPKK['containerBongkarIsi20']) ? null : $getEntryPKK['containerBongkarIsi20'],
                    'unload_40_filled' => empty($getEntryPKK['containerBongkarIsi40']) ? null : $getEntryPKK['containerBongkarIsi40'],

                ]
            );

            if (!$container) throw new \Exception("Gagal menyimpan data Kontainer!");

            $actions = [
                'view',
                'view_any',
                'create',
                'update',
            ];
            $resources = ['request::pandu'];

            $permissions = collect($resources)
                ->flatMap(function ($resource) use ($actions) {
                    return collect($actions)->map(fn($action) => "{$action}_{$resource}");
                })
                ->all();

            $notifiables = User::permission($permissions)->get();

            foreach ($notifiables as $notifiable) {
                $notifiable->notify(
                    Notification::make()
                        ->icon('heroicon-o-arrow-down-tray')
                        ->title('Masuk Pengajuan Kedatangan Kapal Baru!')
                        ->body("Kapal Masuk, No PPK : $nomor_pkk")
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url(RequestPanduResource::getUrl())
                                ->markAsRead(),
                        ])
                        ->success()
                        ->toDatabase()
                );
            }

            DB::commit();

            return $this->sendSuccess('Data Pengajuan Kedatangan Kapal berhasil disimpan!', 'Success');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());

            Notification::make()
                ->title('Ada kesalahan saat mengakses Innaportnet!')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return $this->sendError('Ada kesalahan saat mengakses Innaportnet!', [$exception->getMessage()]);
        }
    }

    public function showAgent($id): JsonResponse
    {
        $user = Auth::user();
        $user = User::find($user?->id);

        if (!$user)
            return $this->sendError("Maaf, Data User $this->notfound_msg");

        $request_arrival = $user->requestArrivals()->where('id', $id)->first();

        if (!$request_arrival)
            return $this->sendError("Maaf, Data Pengajuan Kedatangan Kapal $this->notfound_msg");

        $arrival_request = [
            'nama_kapal' => $request_arrival->vessel_tb?->name,
            'nomor_pkk' => $request_arrival->nomor_pkk,
            'waktu_pengolongan' => $request_arrival->waktu_pengolongan->toDateTimeString(),
            'jenis_pengolongan' => $request_arrival->jenis_pengolongan,
            'lokasi_awal' => $request_arrival->lokasi_awal,
            'lokasi_akhir' => $request_arrival->lokasi_akhir,
            'status' => $request_arrival->status,
        ];

        $pkk = $request_arrival->pkk;
        $rpkro = null;
        if ($pkk->rpkro) {
            $rpkro = [
                'rpkro_number' => $pkk->rpkro->rpkro_number,
                'ppk_number' => $pkk->rpkro->ppk_number,
                'rpkro_type' => $pkk->rpkro->rpkro_type,
                'plan_time' => $pkk->rpkro->plan_time?->toDateTimeString(),
                'tujuan_pelabuhan' => $pkk->rpkro->destination_port_name,
                'note' => $pkk->rpkro->note,
                'status' => $pkk->rpkro->status,
                'rpkro_detail' => [
                    'rkbm_unloading_number' => $pkk->rpkro->rpkroDetail?->rkbm_unloading_number,
                    'rkbm_loading_number' => $pkk->rpkro->rpkroDetail?->rkbm_loading_number,
                    'ppkb_number' => $pkk->rpkro->rpkroDetail?->ppkb_number,
                    'komoditi' => $pkk->rpkro->rpkroDetail?->komoditi,
                    'unloading' => (float)$pkk->rpkro->rpkroDetail?->unloading,
                    'loading' => (float)$pkk->rpkro->rpkroDetail?->loading,
                    'start_time' => $pkk->rpkro->rpkroDetail?->start_time?->toDateTimeString(),
                    'finish_time' => $pkk->rpkro->rpkroDetail?->finish_time?->toDateTimeString(),
                    'initial_meter_code' => (float)$pkk->rpkro->rpkroDetail?->initial_meter_code,
                    'final_meter_code' => (float)$pkk->rpkro->rpkroDetail?->final_meter_code,
                ],
            ];
        }

        $spk_pandu = null;
        if ($pkk->spkPandu) {
            $spk_pandu = [
                'nomor_pkk' => $pkk->spkPandu->nomor_pkk,
                'panjang_kapal' => (float)$pkk->spkPandu->panjang_kapal,
                'lebar_kapal' => (float)$pkk->spkPandu->lebar_kapal,
                'gt_kapal' => $pkk->spkPandu->gt_kapal,
                'no_spk_pandu' => $pkk->spkPandu->no_spk_pandu,
                'nama_petugas' => $pkk->spkPandu->pandu?->name,
                'waktu_pandu' => $pkk->spkPandu->waktu_pandu?->toDateTimeString(),
                'kapal_pandu' => (int)$pkk->spkPandu->kapal_pandu,
                'kapal_tunda' => (int)$pkk->spkPandu->kapal_tunda,
                'lokasi_awal' => $pkk->spkPandu->lokasi_awal,
                'lokasi_akhir' => $pkk->spkPandu->lokasi_akhir,
                'jenis_pandu' => $pkk->spkPandu->jenis_pandu,
                'keperluan' => $pkk->spkPandu->keperluan,
                'waktu_gerak' => $pkk->spkPandu->waktu_gerak?->toDateTimeString(),
                'status' => $pkk->spkPandu->status,
            ];
        }

        $data = [
            'arrival_request' => $arrival_request,
            'rpkro' => $rpkro,
            'spk_pandu' => $spk_pandu,
        ];

        return $this->sendResponse($data, "Data Detail Pengajuan Kedatangan Kapal $this->found_msg");
    }

    public function showPandu($id): JsonResponse
    {
        $user = Auth::user();
        $user = User::find($user?->id);

        if (!$user)
            return $this->sendError("Maaf, Data User $this->notfound_msg");

        $request_arrival = RequestArrival::where('id', $id)->first();

        if (!$request_arrival)
            return $this->sendError("Maaf, Data Pengajuan Kedatangan Kapal $this->notfound_msg");

        $pkk = $request_arrival->pkk;
        $spk_pandu = $pkk->spkPandu;
        $pandu = null;
        $ship_certificate = null;

        if ($spk_pandu) {
            $status = $this->getSPKPanduStatus($spk_pandu->status);
            $pandu = [
                'id' => $spk_pandu->id,
                'nomor_ppk' => $spk_pandu->nomor_ppk,
                'panjang_kapal' => (float)$spk_pandu->panjang_kapal,
                'lebar_kapal' => (float)$spk_pandu->lebar_kapal,
                'gt_kapal' => $spk_pandu->gt_kapal,
                'no_spk_pandu' => $spk_pandu->no_spk_pandu,
                'nama_petugas' => $spk_pandu->pandu?->name,
                'waktu_pandu' => $spk_pandu->waktu_pandu?->toDateTimeString(),
                'kapal_pandu' => (int)$spk_pandu->kapal_pandu,
                'kapal_tunda' => (int)$spk_pandu->kapal_tunda,
                'lokasi_awal' => $spk_pandu->lokasi_awal,
                'lokasi_akhir' => $spk_pandu->lokasi_akhir,
                'jenis_pandu' => $spk_pandu->jenis_pandu,
                'keperluan' => $spk_pandu->keperluan,
                'waktu_gerak' => $spk_pandu->waktu_gerak?->toDateTimeString(),
                'status' => $spk_pandu->status,
            ];
            $ship_certificate = [
                'keperluan' => $request_arrival->jenis_pengolongan,
                'vessel_name' => $pkk->ship->name,
                'barge_name' => $pkk->requestable?->vessel_bg?->code ?? $pkk->requestable?->vessel_bg?->name,
                'vessel_gross_tonnage' => $pkk->ship->grt,
                'barge_gross_tonnage' => $pkk->requestable?->vessel_bg?->grt,
                'call_sign' => $pkk->ship?->call_sign,
                'masters_name' => $pkk->ship?->captain_name,
                'ship_owner' => $request_arrival->user->company?->name,
                'agent_name' => $request_arrival->user?->name,
                'contact_person_agent' => $request_arrival->user?->phone,
                'arrival_from' => $request_arrival->lokasi_awal,
                'next_port_of_call' => $request_arrival->lokasi_akhir,
                'movements_from_to' => $spk_pandu->movements_from_to,
                'pilot_on_board' => $spk_pandu->pilot_on_board,
                'pilotage_finished' => $spk_pandu->pilotage_finished,
                'tag_boad_code' => $spk_pandu->tag_boad_code,
                'signature' => $spk_pandu->signature ? asset('storage/' . $spk_pandu->signature) : null,
                'document' => $spk_pandu->document ? asset('storage/' . $spk_pandu->document) : null,
            ];
        } elseif ($pkk->rpkro) {
            $status = $this->getRPKROStatus($pkk->rpkro->status);
        } else {
            $status = $this->getRequestArrivalStatus($request_arrival->status);
        }

        $arrival_request = [
            'id' => $request_arrival->id,
            'nama_kapal' => $request_arrival->vessel_tb?->name,
            'nomor_pkk' => $request_arrival->nomor_pkk,
            'waktu_pengolongan' => $request_arrival->waktu_pengolongan->toDateTimeString(),
            'jenis_pengolongan' => $request_arrival->jenis_pengolongan,
            'lokasi_awal' => $request_arrival->lokasi_awal,
            'lokasi_akhir' => $request_arrival->lokasi_akhir,
            'status' => $request_arrival->status,
        ];

        $data = [
            'global_status' => $status,
            'arrival_request' => $arrival_request,
            'spk_pandu' => $pandu,
            'ships_services_certificate' => $ship_certificate,
        ];

        return $this->sendResponse($data, "Data Detail Pengajuan Kedatangan Kapal $this->found_msg");
    }
}
