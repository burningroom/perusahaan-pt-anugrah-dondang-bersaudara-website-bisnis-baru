<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\RequestArrival;
use App\Models\User;
use App\Services\InaportnetService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
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

    public function store(Request $request)
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

            $dateTime = Carbon::createFromFormat('Y-m-d H:i', "$tanggal $waktu")->toDateTimeString();
            $vessel_tb = $user->vesselMasters()->where('id', $request->input('vessel_tb'))->where('type', 'tb')->first();

            if (!$vessel_tb) {
                return $this->sendError("Data kapal tugboat $this->notfound_msg");
            }

            $vessel_bg = $user->vesselMasters()->where('id', $request?->vessel_bg)->whereIn('type', ['bg', 'lct'])->first();

            if (!$vessel_bg) {
                return $this->sendError("Data kapal tongkang $this->notfound_msg");
            }

            $responseGetEntryPKK = $this->inaportnetService->getEntryPKK($request?->nomor_pkk);
            if (!$responseGetEntryPKK['success']) throw new \Exception($responseGetEntryPKK['message']);

            return $this->sendResponse($responseGetEntryPKK['data'], 'Data Pengajuan Kedatangan Kapal berhasil disimpan!');
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

//        $responseGetEntryPKK = config('app.env') != 'production' ? $this->_getEntryPKKLocal()->return : $responseGetEntryPKK?->getEntryPKKResult;

//        try {
//
//            DB::transaction(function () use ($request, $dateTime, $user, $responseGetEntryPKK, $vessel_tb, $vessel_bg) {
//
//                $request_arrival = RequestArrival::create([
//                    'user_id' => $user?->id,
//                    'nomor_pkk' => $request?->nomor_pkk,
//                    'vessel_tb' => $request?->vessel_tb,
//                    'vessel_bg' => $request?->vessel_bg,
//                    'rkbm_loading_number' => $request?->rkbm_loading_number,
//                    'rkbm_unloading_number' => $request?->rkbm_unloading_number,
//                    'loading_type' => $request?->loading_type,
//                    'loading' => $request?->loading,
//                    'waktu_pengolongan' => $dateTime,
//                    'jenis_pengolongan' => $request?->jenis_pengolongan,
//                    'lokasi_awal' => $request?->lokasi_awal,
//                    'lokasi_akhir' => $request?->lokasi_akhir,
//                    'status' => 'permintaan',
//                ]);
//
//                if ($request?->vessel_tb) {
//                    $request_arrival->vesselRequestTB()->create([
//                        'registration_sign' => $vessel_tb?->registration_sign,
//                        'name' => $vessel_tb?->name,
//                        'code' => $vessel_tb?->code,
//                        'type' => $vessel_tb?->type,
//                        'drt' => (int)$vessel_tb?->drt,
//                        'grt' => (int)$vessel_tb?->grt,
//                        'loa' => (float)$vessel_tb?->loa,
//                        'kind' => $vessel_tb?->kind,
//                        'width' => (float)$vessel_tb?->width,
//                        'max_draft' => (float)$vessel_tb?->max_draft,
//                        'front_draft' => (float)$vessel_tb?->front_draft,
//                        'back_draft' => (float)$vessel_tb?->back_draft,
//                        'central_draft' => (float)$vessel_tb?->central_draft,
//                        'route_type' => $vessel_tb?->route_type,
//                        'flag' => $vessel_tb?->flag,
//                        'call_sign' => $vessel_tb?->call_sign,
//                        'imo_number' => $vessel_tb?->imo_number,
//                    ]);
//                }
//
//                if ($request?->vessel_bg) {
//                    $request_arrival->vesselRequestBG()->create([
//                        'registration_sign' => $vessel_bg?->registration_sign,
//                        'name' => $vessel_bg?->name,
//                        'code' => $vessel_bg?->code,
//                        'type' => $vessel_bg?->type,
//                        'drt' => (int)$vessel_bg?->drt,
//                        'grt' => (int)$vessel_bg?->grt,
//                        'loa' => (float)$vessel_bg?->loa,
//                        'kind' => $vessel_bg?->kind,
//                        'width' => (float)$vessel_bg?->width,
//                        'max_draft' => (float)$vessel_bg?->max_draft,
//                        'front_draft' => (float)$vessel_bg?->front_draft,
//                        'back_draft' => (float)$vessel_bg?->back_draft,
//                        'central_draft' => (float)$vessel_bg?->central_draft,
//                        'route_type' => $vessel_bg?->route_type,
//                        'flag' => $vessel_bg?->flag,
//                        'call_sign' => $vessel_bg?->call_sign,
//                        'imo_number' => $vessel_bg?->imo_number,
//                    ]);
//                }
//
//                $request_arrival->userRequest()->create([
//                    'name' => $user?->name,
//                    'phone' => $user?->phone,
//                    'email' => $user?->email,
//                ]);
//
//                $request_arrival->companyRequest()->create([
//                    'name' => $user?->company?->name,
//                    'npwp' => $user?->company?->npwp,
//                    'address' => $user?->company?->address,
//                    'city' => $user?->company?->city,
//                    'state' => $user?->company?->state,
//                    'country' => $user?->company?->country,
//                    'postal_code' => $user?->company?->postal_code,
//                    'phone' => $user?->company?->phone,
//                    'email' => $user?->company?->email,
//                    'website' => $user?->company?->website,
//                ]);
//
//                $pkk = $request_arrival->pkk()->updateOrCreate(
//                    [
//                        'pkk_number' => $request?->nomor_pkk,
//                    ],
//                    [
//                        'pkk_number' => $request?->nomor_pkk,
//                    ]
//                );
//
//                $pkk?->spkPandu()->updateOrCreate(
//                    [
//                        'pkk_id' => $pkk?->id,
//                        'agent_id' => $user?->id,
//                    ],
//                    [
//                        'pkk_id' => $pkk?->id,
//                        'nomor_pkk' => $pkk?->pkk_number,
//                        'agent_id' => $user?->id,
//                        'status' => 'permintaan',
//                    ]
//                );
//
//                if (in_array($responseGetEntryPKK?->statusMessage, ['Sukses', 'Success'])) {
//                    $getEntryPKK = $responseGetEntryPKK;
//                    $pkk = $request_arrival->pkk()->updateOrCreate(
//                        [
//                            'pkk_number' => config('app.env') == 'production' ? (empty($getEntryPKK?->nomorPKK) ? null : $getEntryPKK?->nomorPKK) : $request?->nomor_pkk,
//                        ],
//                        [
//                            'pkk_number' => config('app.env') == 'production' ? (empty($getEntryPKK?->nomorPKK) ? null : $getEntryPKK?->nomorPKK) : $request?->nomor_pkk,
//                            'route_type' => empty($getEntryPKK?->jenisTrayek) ? null : $getEntryPKK?->jenisTrayek,
//                            'route_number' => empty($getEntryPKK?->nomorTrayek) ? null : $getEntryPKK?->nomorTrayek,
//                            'bm_status' => empty($getEntryPKK?->statusBM) ? null : $getEntryPKK?->statusBM,
//                            'total_unload' => empty($getEntryPKK?->jumlahBongkar) ? 0 : (int)$getEntryPKK?->jumlahBongkar,
//                            'total_load' => empty($getEntryPKK?->jumlahMuat) ? 0 : (int)$getEntryPKK?->jumlahMuat,
//                            'port_code' => empty($getEntryPKK?->portCode) ? null : $getEntryPKK?->portCode[0],
//                            'item_type' => empty($getEntryPKK?->jenisBarang) ? null : $getEntryPKK?->jenisBarang,
//                            'status' => 'sukses',
//                            'description' => 'Inaportnet : Sukses',
//                            'tanggalEta' => empty($getEntryPKK?->tanggalEta) ? null : Carbon::parse($getEntryPKK?->tanggalEta)->toDateTimeString(),
//                            'tanggalEtd' => empty($getEntryPKK?->tanggalEtd) ? null : Carbon::parse($getEntryPKK?->tanggalEtd)->toDateTimeString(),
//                            'pmku_pandu_number' => empty($getEntryPKK?->noPmkuPandu) ? null : $getEntryPKK?->noPmkuPandu,
//                            'npwp_pandu_number' => empty($getEntryPKK?->noNpwpPandu) ? null : $getEntryPKK?->noNpwpPandu,
//                            'pandu_name' => empty($getEntryPKK?->namaPandu) ? null : $getEntryPKK?->namaPandu,
//                            'mmsi' => empty($getEntryPKK?->mmsi) ? null : $getEntryPKK?->mmsi,
//                            'status_window' => empty($getEntryPKK?->statusWindow) ? null : $getEntryPKK?->statusWindow,
//                        ]
//                    );
//
//                    $pkk?->port()->updateOrCreate(
//                        [
//                            'pkk_id' => $pkk?->id,
//                        ],
//                        [
//                            'pkk_id' => $pkk?->id,
//                            'port_code' => empty($getEntryPKK?->portCode) ? null : $getEntryPKK?->portCode[1],
//                            'port_name' => empty($getEntryPKK?->muatPelabuhan) ? null : $getEntryPKK?->muatPelabuhan,
//                            'port_origin_code' => empty($getEntryPKK?->kodeAsalPelabuhan) ? null : $getEntryPKK?->kodeAsalPelabuhan,
//                            'origin_port' => empty($getEntryPKK?->asalPelabuhan) ? null : $getEntryPKK?->asalPelabuhan,
//                            'load_port_code' => empty($getEntryPKK?->kodeMuatPelabuhan) ? null : $getEntryPKK?->kodeMuatPelabuhan,
//                            'load_port_name' => empty($getEntryPKK?->muatPelabuhan) ? null : $getEntryPKK?->muatPelabuhan,
//                            'destination_port_code' => empty($getEntryPKK?->kodeTujuanPelabuhan) ? null : $getEntryPKK?->kodeTujuanPelabuhan,
//                            'destination_port_name' => empty($getEntryPKK?->tujuanPelabuhan) ? null : $getEntryPKK?->tujuanPelabuhan,
//                            'final_destination_port_code' => empty($getEntryPKK?->kodeTujuanAkhirPelabuhan) ? null : $getEntryPKK?->kodeTujuanAkhirPelabuhan,
//                            'final_destination_port_name' => empty($getEntryPKK?->tujuanAkhirPelabuhan) ? null : $getEntryPKK?->tujuanAkhirPelabuhan,
//                        ]
//                    );
//
//                    $pkk?->terminal()->updateOrCreate(
//                        [
//                            'pkk_id' => $pkk?->id,
//                        ],
//                        [
//                            'pkk_id' => $pkk?->id,
//                            'dock_code' => empty($getEntryPKK?->kodeDermaga) ? null : $getEntryPKK?->kodeDermaga,
//                            'dock_name' => empty($getEntryPKK?->dermagaNama) ? null : $getEntryPKK?->dermagaNama,
//                        ]
//                    );
//
//                    $pkk?->principal()->updateOrCreate(
//                        [
//                            'pkk_id' => $pkk?->id,
//                        ],
//                        [
//                            'pkk_id' => $pkk?->id,
//                            'company_id' => empty($getEntryPKK?->perusahaanNama) || empty($getEntryPKK?->npwp) ? null : Company::where('name', 'ILIKE', "%$getEntryPKK?->perusahaanNama%")->where('npwp', 'ILIKE', "%$getEntryPKK?->npwp%")->first()?->id,
//                            'company_name' => empty($getEntryPKK?->perusahaanNama) ? null : $getEntryPKK?->perusahaanNama,
//                            'npwp' => empty($getEntryPKK?->npwp) ? null : $getEntryPKK?->npwp,
//                            'principal_npwp' => empty($getEntryPKK?->npwpPrincipal) ? null : $getEntryPKK?->npwpPrincipal,
//                            'principal_name' => empty($getEntryPKK?->namaPrincipal) ? null : $getEntryPKK?->namaPrincipal,
//                            'principal_country' => empty($getEntryPKK?->negaraPrincipal) ? null : $getEntryPKK?->negaraPrincipal,
//                            'but_status' => empty($getEntryPKK?->statusBut) ? null : $getEntryPKK?->statusBut,
//                        ]
//                    );
//
//                    $pkk?->ship()->updateOrCreate(
//                        [
//                            'pkk_id' => $pkk?->id,
//                        ],
//                        [
//                            'pkk_id' => $pkk?->id,
//                            'registration_number' => empty($getEntryPKK?->tandaPendaftaranKapal) ? null : $getEntryPKK?->tandaPendaftaranKapal,
//                            'name' => empty($getEntryPKK?->kapalNama) ? null : $getEntryPKK?->kapalNama,
//                            'captain_name' => empty($getEntryPKK?->nahkoda) ? null : $getEntryPKK?->nahkoda,
//                            'drt' => empty($getEntryPKK?->drt) ? null : (int)$getEntryPKK?->drt,
//                            'grt' => empty($getEntryPKK?->grt) ? null : (int)$getEntryPKK?->grt,
//                            'loa' => empty($getEntryPKK?->loa) ? null : (float)$getEntryPKK?->loa,
//                            'ship_type' => empty($getEntryPKK?->jenisKapal) ? null : $getEntryPKK?->jenisKapal,
//                            'year_build' => empty($getEntryPKK?->tahunPembuatan) ? null : (int)$getEntryPKK?->tahunPembuatan,
//                            'width' => empty($getEntryPKK?->lebarKapal) ? null : (float)$getEntryPKK?->lebarKapal,
//                            'max_draft' => empty($getEntryPKK?->drMax) ? null : (float)$getEntryPKK?->drMax,
//                            'front_draft' => empty($getEntryPKK?->drDepan) ? null : (float)$getEntryPKK?->drDepan,
//                            'rear_draft' => empty($getEntryPKK?->drBelakang) ? null : (float)$getEntryPKK?->drBelakang,
//                            'midship_draft' => empty($getEntryPKK?->drTengah) ? null : (float)$getEntryPKK?->drTengah,
//                            'call_sign' => empty($getEntryPKK?->callSign) ? null : $getEntryPKK?->callSign,
//                            'flag' => empty($getEntryPKK?->bendera) ? null : $getEntryPKK?->bendera,
//                        ]
//                    );
//
//                    $pkk?->cargo()->updateOrCreate(
//                        [
//                            'pkk_id' => $pkk?->id,
//                        ],
//                        [
//                            'pkk_id' => $pkk?->id,
//                            'mixed_cargo_unload' => empty($getEntryPKK?->cargoBarangCampurBongkar) ? null : $getEntryPKK?->cargoBarangCampurBongkar,
//                            'mixed_cargo_load' => empty($getEntryPKK?->cargoBarangCampurMuat) ? null : $getEntryPKK?->cargoBarangCampurMuat,
//                            'dangerous_good_cargo_unload' => empty($getEntryPKK?->cargoBarangBerbahayaBongkar) ? null : $getEntryPKK?->cargoBarangBerbahayaBongkar,
//                            'dangerous_good_cargo_unload' => empty($getEntryPKK?->cargoBarangBerbahayaMuat) ? null : $getEntryPKK?->cargoBarangBerbahayaMuat,
//                            'unload_amount' => empty($getEntryPKK?->jumlahBongkar) ? null : $getEntryPKK?->jumlahBongkar,
//                        ]
//                    );
//
//                    $pkk?->container()->updateOrCreate(
//                        [
//                            'pkk_id' => $pkk?->id,
//                        ],
//                        [
//                            'pkk_id' => $pkk?->id,
//                            'load_20_filled' => empty($getEntryPKK?->containerMuatIsi20) ? null : $getEntryPKK?->containerMuatIsi20,
//                            'load_40_filled' => empty($getEntryPKK?->containerMuatIsi40) ? null : $getEntryPKK?->containerMuatIsi40,
//                            'unload_20_filled' => empty($getEntryPKK?->containerBongkarIsi20) ? null : $getEntryPKK?->containerBongkarIsi20,
//                            'unload_40_filled' => empty($getEntryPKK?->containerBongkarIsi40) ? null : $getEntryPKK?->containerBongkarIsi40,
//
//                        ]
//                    );
//                }
//            });
//            DB::commit();
//        } catch (Exception|Throwable $th) {
//            DB::rollBack();
//            Log::error($th->getMessage());
//            Log::error('Ada kesalahan saat getEntryPKK data from Innaportnet!');
//
//            return $this->sendError('Ada kesalahan saat getEntryPKK data from Innaportnet!');
//        }

//        $notifiables = User::whereHas('roles', function ($query) {
//            $query->whereIn('name', ['super-admin', 'admin-bup']);
//        })->get();
//
//        foreach ($notifiables as $key => $notifiable) {
//            $notifiable->notify(
//                Notification::make()
//                    ->icon('heroicon-o-arrow-down-tray')
//                    ->title('Masuk Pengajuan Kedatangan Kapal Baru!')
//                    ->body("Kapal Masuk, No PPK : $request?->nomor_pkk")
//                    ->actions([
//                        Action::make('view')
//                            ->button()
//                            ->url(RequestArrivalResource::getUrl())
//                            ->markAsRead(),
//                    ])
//                    ->success()
//                    ->toDatabase()
//            );
//        }
//
//        return $this->sendSuccess('Data Pengajuan Kedatangan Kapal berhasil disimpan!');
    }
}
