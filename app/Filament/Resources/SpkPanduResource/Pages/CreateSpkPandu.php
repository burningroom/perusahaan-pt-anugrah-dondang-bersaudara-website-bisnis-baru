<?php

namespace App\Filament\Resources\SpkPanduResource\Pages;

use App\Filament\Resources\SpkPanduResource;
use App\Models\RequestArrival;
use App\Models\SpkPandu;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateSpkPandu extends Page implements HasForms
{
    protected static string $resource = SpkPanduResource::class;

    protected static string $view = 'filament.pages.spk-pandu.create';

    protected static ?string $title = 'Buat Data SPK Pandu';

    protected ?string $heading = 'Buat Data SPK Pandu';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'is_pkk_found' => false,
            'request_arrival' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Section::make('Cek Nomor PKK')
                    ->columns(12)
                    ->schema([
                        Select::make('request_arrival')
                            ->label('Pilih Permintaan Pandu')
                            ->options(function () {
                                return RequestArrival::where('status', 'setuju')->whereHas('pkk.spkPandu', fn($query) => $query->whereNull('user_id'))->latest()->get()
                                    ->mapWithKeys(function ($item) {
                                        return [$item?->id => "{$item?->nomor_pkk} - {$item?->vessel_tb?->name} - " . Str::title($item?->jenis_pengolongan)];
                                    })->toArray();
                            })
                            ->native(false)
                            ->required()
                            ->searchable()
                            ->columnSpan(12)
                    ])
                    ->footerActions([
                        Action::make('get-Entry-PKK')
                            ->label('Cek Nomor')
                            ->action('getEntryPKK')
                    ])
                    ->footerActionsAlignment(Alignment::End)
                    ->hidden(fn(Get $get) => $get('is_pkk_found'))
                    ->columnSpan(12),
                Section::make('Surat Perintah Kerja Pandu')
                    ->columns(12)
                    ->schema([
                        Fieldset::make('Data Kapal')
                            ->columns(12)
                            ->schema([
                                TextInput::make('nomorPKK')
                                    ->label('Nomor PKK')
                                    ->disabled()
                                    ->columnSpan(4),
                                TextInput::make('kapalNama')
                                    ->label('Nama Kapal')
                                    ->disabled()
                                    ->columnSpan(4),
                                DateTimePicker::make('tanggalEta')
                                    ->label('Perkiraan Kedatangan Kapal')
                                    ->displayFormat('d F Y H:i')
                                    ->native(false)
                                    ->disabled()
                                    ->columnSpan(4),
                                TextInput::make('perusahaanNama')
                                    ->label('Nama Perusahaan')
                                    ->disabled()
                                    ->columnSpan(6),
                                TextInput::make('dermagaNama')
                                    ->label('Nama Dermage')
                                    ->disabled()
                                    ->columnSpan(6),
                            ]),
                        Fieldset::make('Data Surat Perintah Kerja Pandu')
                            ->columns(12)
                            ->schema([
                                TextInput::make('nomor_ppk')
                                    ->label('Nomor PPK')
                                    ->readOnly()
                                    ->disabled()
                                    ->columnSpan(3),
                                TextInput::make('panjang_kapal')
                                    ->label('Panjang Kapal')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->readOnly()
                                    ->columnSpan(3),
                                TextInput::make('lebar_kapal')
                                    ->label('Lebar Kapal')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->readOnly()
                                    ->columnSpan(3),
                                TextInput::make('gt_kapal')
                                    ->label('GT Kapal')
                                    ->required()
                                    ->readOnly()
                                    ->columnSpan(3),
                                TextInput::make('no_spk_pandu')
                                    ->label('No. SPK Pandu')
                                    ->required()
                                    ->columnSpan(4),
                                Select::make('user_id')
                                    ->label('Petugas Pandu')
                                    ->options(function() {
                                        return User::whereHas('roles', fn($query) => $query->whereIn('name', ['pandu']))->pluck('name', 'id')->toArray();
                                })
                            ->searchable()
                            ->native(false)
                            ->required()
                            ->columnSpan(4),
                        DatePicker::make('tanggal_pandu')
                            ->label('Tanggal Pandu')
                            ->displayFormat('d F Y')
                            ->native(false)
                            ->required()
                            ->columnSpan(2),
                        TimePicker::make('jam_pandu')
                            ->label('Jam Pandu')
                            ->format('H:i')
                            ->displayFormat('H:i')
                            ->seconds(false)
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('kapal_pandu')
                            ->label('Kapal Pandu')
                            ->minValue(0)
                            ->maxValue(2)
                            ->numeric()
                            ->required()
                            ->suffix('Kapal')
                            ->helperText('Max 2 Kapal')
                            ->columnSpan(2),
                        TextInput::make('kapal_tunda')
                            ->label('Kapal Tunda')
                            ->minValue(0)
                            ->maxValue(4)
                            ->required()
                            ->numeric()
                            ->suffix('Kapal')
                            ->helperText('Max 4 Kapal')
                            ->columnSpan(2),
                        TextInput::make('lokasi_awal')
                            ->label('Lokasi Awal')
                            ->required()
                            ->columnSpan(4),
                        TextInput::make('lokasi_akhir')
                            ->label('Lokasi Akhir')
                            ->required()
                            ->columnSpan(4),
                        Select::make('jenis_pandu')
                            ->label('Jenis Pandu')
                            ->options([
                                'sungai' => 'Sungai',
                                'laut' => 'Laut',
                                'bandar' => 'Bandar',
                                'luar-biasa' => 'Luar Biasa',
                            ])
                            ->native(false)
                            ->columnSpan(4),
                        Select::make('keperluan')
                            ->label('Keperluan')
                            ->options([
                                'masuk' => 'Masuk',
                                'pindah' => 'Pindah',
                                // 'labuh'  => 'Labuh',
                                'keluar' => 'Keluar',
                            ])
                            ->native(false)
                            ->required()
                            ->columnSpan(4),
                        DateTimePicker::make('waktu_gerak')
                            ->label('Waktu Gerak')
                            ->required()
                            ->seconds(false)
                            ->columnSpan(4),
                    ]),
            ])
            ->hidden(fn(Get $get) => !$get('is_pkk_found')),
            ])->statePath('data');
    }

    public function getEntryPKK()
    {
        $this->validate([
            'data.request_arrival' => 'required',
        ]);

        $request_arrival = RequestArrival::find($this->data['request_arrival'])?->pkk;

        $this->data['is_pkk_found'] = false;
        if (!$request_arrival) {
            return Notification::make()
                ->title('Gagal')
                ->body('Nomor PKK Terkait Tidak Ditemukan Di Database...')
                ->danger()
                ->send();
        }

        $this->data['nomorPKK'] = $request_arrival->pkk_number;
        $this->data['kapalNama'] = $request_arrival->ship?->name;
        $this->data['tanggalEta'] = $request_arrival->tanggalEta;
        $this->data['perusahaanNama'] = $request_arrival->principal?->company_name;
        $this->data['dermagaNama'] = $request_arrival->terminal?->dock_name;
        $this->data['destination_port_name'] = $request_arrival->port?->destination_port_name;
        $this->data['nomor_ppk'] = $request_arrival->rpkro?->ppk_number;
        $this->data['panjang_kapal'] = $request_arrival->ship?->loa;
        $this->data['lebar_kapal'] = $request_arrival->ship?->width;
        $this->data['gt_kapal'] = $request_arrival->ship?->grt;

        $this->data['tanggal_pandu'] = $request_arrival->requestable?->waktu_pengolongan?->toDateString();
        $this->data['jam_pandu'] = $request_arrival->requestable?->waktu_pengolongan?->format('H:i');
        $this->data['lokasi_awal'] = $request_arrival->requestable?->lokasi_awal;
        $this->data['lokasi_akhir'] = $request_arrival->requestable?->lokasi_akhir;
        $this->data['keperluan'] = $request_arrival->requestable?->jenis_pengolongan;
        $this->data['is_pkk_found'] = true;

        return Notification::make()
            ->title('Berhasil')
            ->body('Nomor PKK Terkait Ditemukan Di Database...')
            ->success()
            ->send();
    }

    public function createSPKPandu()
    {
        $this->validate([
            'data.nomorPKK' => 'required|string',
            'data.nomor_ppk' => 'nullable|string',
            'data.panjang_kapal' => 'required|numeric',
            'data.lebar_kapal' => 'required|numeric',
            'data.gt_kapal' => 'required',
            'data.no_spk_pandu' => 'required',
            'data.user_id' => 'required',
            'data.tanggal_pandu' => 'required|date',
            'data.jam_pandu' => 'required|date_format:H:i',
            'data.kapal_pandu' => 'required|numeric',
            'data.kapal_tunda' => 'required|numeric',
            'data.lokasi_awal' => 'required',
            'data.lokasi_akhir' => 'required',
            'data.jenis_pandu' => 'required',
            'data.keperluan' => 'required',
            'data.waktu_gerak' => 'required',
        ]);

        try {
            $tanggal = $this->data['tanggal_pandu'];
            $jam = $this->data['jam_pandu'];
            $waktu_pandu = Carbon::createFromFormat('Y-m-d H:i', "$tanggal $jam")->toDateTimeString();
            $get_pkk = RequestArrival::find($this->data['request_arrival'])?->pkk;

            DB::beginTransaction();
            $spk_pandu = SpkPandu::updateOrCreate(
                [
                    'pkk_id' => $get_pkk?->id
                ],
                [
                    'user_id' => $this->data['user_id'] ?? null,
                    'agent_id' => $get_pkk?->requestable?->user?->id,
                    'pkk_id' => $get_pkk?->id ?? null,
                    'nomor_pkk' => $this->data['nomorPKK'] ?? null,
                    'nomor_ppk' => $this->data['nomor_ppk'] ?? null,
                    'panjang_kapal' => isset($this->data['panjang_kapal']) ? (double)$this->data['panjang_kapal'] : null,
                    'lebar_kapal' => isset($this->data['lebar_kapal']) ? (double)$this->data['lebar_kapal'] : null,
                    'gt_kapal' => $this->data['gt_kapal'] ?? null,
                    'no_spk_pandu' => $this->data['no_spk_pandu'] ?? null,
                    'waktu_pandu' => $waktu_pandu ?? null,
                    'kapal_pandu' => isset($this->data['kapal_pandu']) ? (int)$this->data['kapal_pandu'] : null,
                    'kapal_tunda' => isset($this->data['kapal_tunda']) ? (int)$this->data['kapal_tunda'] : null,
                    'lokasi_awal' => $this->data['lokasi_awal'] ?? null,
                    'lokasi_akhir' => $this->data['lokasi_akhir'] ?? null,
                    'jenis_pandu' => $this->data['jenis_pandu'] ?? null,
                    'keperluan' => $this->data['keperluan'] ?? null,
                    'waktu_gerak' => isset($this->data['waktu_gerak']) ? Carbon::parse($this->data['waktu_gerak'])->toDateTimeString() : null,
                    'status' => $get_pkk?->spkPandu != null ? 'setuju' : $get_pkk?->spkPandu?->status,
                ]
            );

            // $params = [
            //     'user'             => config('app.WSDL.INAPORT_USER'),
            //     'password'         => config('app.WSDL.INAPORT_PASSWORD'),
            //     'NomorPKK'         => $spk_pandu?->nomor_pkk,
            //     'NomorPPK'         => $spk_pandu?->nomor_ppk,
            //     'NomorSPKPandu'    => $spk_pandu?->no_spk_pandu,
            //     'NamaPetugasPandu' => $spk_pandu?->user?->name,
            //     'TanggalPandu'     => Carbon::parse($spk_pandu?->waktu_pandu)->toDateString(),
            //     'JamPandu'         => Carbon::parse($spk_pandu?->waktu_pandu)->toTimeString(),
            //     // 'NamaKapalPandu1'  => $spk_pandu?->kapal_pandu,
            //     // 'NamaKapalPandu2'  => $spk_pandu?->kapal_pandu2,
            //     // 'NamaKapalTunda1'  => $spk_pandu?->kapal_tunda,
            //     // 'NamaKapalTunda2'  => $spk_pandu?->kapal_tunda2,
            //     // 'NamaKapalTunda3'  => $spk_pandu?->kapal_tunda3,
            //     // 'NamaKapalTunda4'  => $spk_pandu?->kapal_tunda4,
            //     // 'JenisPandu'       => $spk_pandu?->jenis_pandu,
            //     'LokasiAwal'       => $spk_pandu?->lokasi_awal,
            //     'LokasiAkhir'      => $spk_pandu?->lokasi_akhir,
            //     'Kegiatan'         => $spk_pandu?->keperluan,
            //     'WaktuGerak'       => Carbon::parse($spk_pandu?->waktu_gerak)->toDateTimeString(),
            //     'NomorLayanan'     => $spk_pandu?->nomor_pkk,
            // ];

            // $responseSetSpkPandu = config('app.env') == 'production' ? $this->SoapClient->__soapCall('setSpkPandu', [$params]) : $this->_setSpkPanduLocal();
            // // dd($responseSetSpkPandu);
            // $api_statusCode      = $responseSetSpkPandu?->SetSpkPanduResult?->return?->statusCode;
            // $api_statusMessage   = $responseSetSpkPandu?->SetSpkPanduResult?->return?->statusMessage;
            // $api_function        = 'setSpkPandu-'.$spk_pandu?->nomor_pkk.'-'.$spk_pandu->nomor_ppk;

            // if ($api_statusCode != '01') {
            //     $spk_pandu->update([
            //         'status' => 'permintaan'
            //     ]);
            //     throw new \Exception( $api_function. '=>' .$api_statusMessage);
            // }
            DB::commit();
        } catch (\Exception|\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            Log::error('Ada kesalahan saat menyimpan data SPK Pandu!');
            return Notification::make()
                ->title('Gagal')
                ->body('Ada kesalahan saat menyimpan data SPK Pandu!')
                ->danger()
                ->send();
        }

        redirect(SpkPanduResource::getUrl());
        return Notification::make()
            ->title('Berhasil')
            ->body('Data Surat Perintah Kerja Pandu berhasil di simpan!')
            ->success()
            ->send();
    }
}
