<?php

namespace App\Filament\Resources\RpkroResource\Pages;

use App\Models\RequestArrival;
use App\Models\Rpkro;
use App\Services\InaportnetService;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\RpkroResource;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateRpkro extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = RpkroResource::class;

    protected static string $view = 'filament.pages.rpkro.create';

    protected static ?string $title = 'Buat Data RPKRO';

    protected ?string $heading = 'Buat Data RPKRO';

    public ?array $data = [];

    public function mount()
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
                                return RequestArrival::where('status', 'setuju')->whereDoesntHave('pkk.rpkro')->latest()->get()
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
                Section::make('Data Kapal')
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
                            ->columnSpan(4),
                        TextInput::make('dermagaKode')
                            ->label('Nama Dermaga')
                            ->disabled()
                            ->columnSpan(4),
                        TextInput::make('dermagaNama')
                            ->label('Nama Dermaga')
                            ->disabled()
                            ->columnSpan(4),
                    ])
                    ->hidden(fn(Get $get) => !$get('is_pkk_found')),
                Section::make('Data RPKRO')
                    ->columns(12)
                    ->schema([
                        Fieldset::make('RPKRO')
                            ->columns(12)
                            ->schema([
                                // ...
                                TextInput::make('rpkro_number')
                                    ->label('Nomor RPKRO')
                                    ->required()
                                    ->columnSpan(6),
                                Select::make('rpkro_type')
                                    ->label('Jenis RPKRO')
                                    ->options([
                                        'masuk' => 'Masuk',
                                        'pindah' => 'Pindah',
                                        'keluar' => 'Keluar',
                                        'perpanjang' => 'Perpanjang',
                                    ])
                                    ->native(false)
                                    ->required()
                                    ->columnSpan(6),
                                DatePicker::make('plan_date')
                                    ->label('Tanggal Rencana')
                                    // ->format('d/m/Y')
                                    ->displayFormat('d F Y')
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(3),
                                TimePicker::make('plan_time')
                                    ->label('Waktu Rencana')
                                    ->required()
                                    ->seconds(false)
                                    ->columnSpan(2),
                                TextInput::make('destination_port_name')
                                    ->label('Lokasi Sandar')
                                    ->required()
                                    ->disabled()
                                    ->columnSpan(7),
                                Textarea::make('note')
                                    ->label('Keterangan')
                                    ->autosize()
                                    ->columnSpanFull()
                            ]),
                        Fieldset::make('Detail RPKRO')
                            ->columns(12)
                            ->schema([
                                TextInput::make('rkbm_unloading_number')
                                    ->label('Nomor RKBM Bongkar')
                                    ->columnSpan(3),
                                TextInput::make('rkbm_loading_number')
                                    ->label('Nomor RKBM Muat')
                                    ->columnSpan(3),
                                TextInput::make('ppkb_number')
                                    ->label('Nomor PPKB')
                                    ->required()
                                    ->columnSpan(3),
                                TextInput::make('komoditi')
                                    ->label('Komoditi')
                                    ->columnSpan(3),
                                TextInput::make('unloading')
                                    ->label('Bongkar')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('TON')
                                    ->columnSpan(2),
                                TextInput::make('loading')
                                    ->label('Muat')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('TON')
                                    ->columnSpan(2),
                                DatePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    // ->format('Y-m-d')
                                    ->displayFormat('d F Y')
                                    ->native(false)
                                    ->required()
                                    ->columnSpan(2),
                                TimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->required()
                                    // ->native(false)
                                    ->seconds(false)
                                    ->columnSpan(2),
                                DatePicker::make('finish_date')
                                    ->label('Tanggal Selesai')
                                    // ->format('Y-m-d')
                                    ->displayFormat('d F Y')
                                    ->native(false)
                                    ->required()
                                    ->columnSpan(2),
                                TimePicker::make('finish_time')
                                    ->label('Waktu Selesai')
                                    ->required()
                                    // ->native(false)
                                    ->format('H:i')
                                    ->displayFormat('H:i')
                                    ->seconds(false)
                                    ->columnSpan(2),
                                TextInput::make('initial_meter_code')
                                    ->label('Kode Meter Awal')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->columnSpan(2),
                                TextInput::make('final_meter_code')
                                    ->label('Kode Meter Akhir')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->columnSpan(2),
                            ])
                    ])
                    ->hidden(fn(Get $get) => !$get('is_pkk_found')),
            ])->statePath('data');
    }

    public function getEntryPKK()
    {
        try {
            $this->validate([
                'data.request_arrival' => 'required',
            ], [
                'required' => 'Permintaan pandu belum dipilih'
            ]);

            $request_arrival_id = $this->data['request_arrival'];

            $request_arrival = RequestArrival::find($request_arrival_id);
            $pkk = $request_arrival->pkk;
            if (!$pkk) throw new \Exception("Permintaan pandu tidak mempunyai PKK");

            if ($pkk->rpkro) throw new \Exception("Nomor PKK Terkait Sudah Memiliki Data RPKRO");

            $request_arrival->update([
                'status' => 'setuju'
            ]);

            $this->data['nomorPKK'] = $pkk->pkk_number;
            $this->data['kapalNama'] = $pkk->ship?->name;
            $this->data['tanggalEta'] = $pkk->tanggalEta;
            $this->data['perusahaanNama'] = $pkk->principal?->company_name;
            $this->data['dermagaKode'] = $pkk->terminal?->dock_code;
            $this->data['dermagaNama'] = $pkk->terminal?->dock_name;

            $this->data['plan_date'] = Carbon::parse($request_arrival->waktu_pengolongan)?->toDateString();
            $this->data['plan_time'] = Carbon::parse($request_arrival->waktu_pengolongan)?->format('H:i');
            $this->data['destination_port_name'] = $pkk->port?->destination_port_name;
            $this->data['rpkro_type'] = $request_arrival->jenis_pengolongan;

            $this->data['rkbm_unloading_number'] = $request_arrival->rkbm_unloading_number;
            $this->data['rkbm_loading_number'] = $request_arrival->rkbm_loading_number;
            $this->data['komoditi'] = $request_arrival->loading_type;
            $this->data['unloading'] = $pkk->total_unload;
            $this->data['loading'] = $pkk->total_load;
            $this->data['is_pkk_found'] = true;

            return Notification::make()
                ->title('Berhasil')
                ->body('Nomor PKK Terkait Ditemukan Di Database...')
                ->success()
                ->send();
        } catch (\Exception $e) {
            throw ValidationException::withMessages(["data.request_arrival" => $e->getMessage()]);
        }
    }

    public function createRPKRO()
    {
        try {
            $this->validate([
                'data.nomorPKK' => 'required|string',
                'data.rpkro_number' => 'required|string',
                'data.rpkro_type' => 'required:string',
                'data.plan_date' => 'required|date',
                'data.plan_time' => 'required|date_format:H:i',
                'data.destination_port_name' => 'nullable|string',
                'data.note' => 'nullable|string',
                'data.rkbm_number' => 'nullable|string',
                'data.ppkb_number' => 'required|string',
                'data.komoditi' => 'nullable|string',
                'data.unloading' => 'nullable|numeric',
                'data.loading' => 'nullable|numeric',
                'data.start_date' => 'required|date',
                'data.start_time' => 'required|date_format:H:i',
                'data.finish_date' => 'required|date',
                'data.finish_time' => 'required|date_format:H:i',
                'data.initial_meter_code' => 'required|numeric',
                'data.final_meter_code' => 'required|numeric',
            ]);

            DB::beginTransaction();

            $request_arrival_id = $this->data['request_arrival'];
            $request_arrival = RequestArrival::find($request_arrival_id);
            if (!$request_arrival) throw new \Exception("Permintaan pandu tidak ditemukan");
            $pkk = $request_arrival->pkk;
            if (!$pkk) throw new \Exception("Nomor PKK Terkait Tidak Ditemukan Di Database...");

            $rpkro = $pkk->rpkro()->where('rpkro_number', $this->data['rpkro_number'])->first();
            if ($rpkro) throw new \Exception("Nomor RPKRO Terkait Sudah Ada Di Database...");

            $plan_date = $this->data['plan_date'];
            $plan_date = $plan_date ? Carbon::parse($plan_date)->toDateString() : null;
            $plan_time = $this->data['plan_time'];
            $planDate = filled($plan_date) && filled($plan_time)
                ? Carbon::parse("$plan_date $plan_time")->toDateTimeString()
                : null;

            $rpkro = Rpkro::create(
                [
                    'pkk_id' => $pkk->id,
                    'user_id' => $pkk->user_id,
                    'rpkro_number' => $this->data['rpkro_number'] ?? null,
                    'rpkro_type' => $this->data['rpkro_type'] ?? null,
                    'plan_time' => $planDate,
                    'destination_port_name' => $this->data['destination_port_name'] ?? null,
                    'note' => $this->data['note'] ?? null,
                    'status' => 'permintaan'
                ]
            );

            if (!$rpkro) throw new \Exception("Gagal membuat data RPKRO");

            $start_date = $this->data['start_date'] ?? null;
            $start_date = $start_date ? Carbon::parse($start_date)->toDateString() : null;
            $start_time = $this->data['start_time'] ?? null;
            $finish_date = $this->data['finish_date'] ?? null;
            $finish_date = $finish_date ? Carbon::parse($finish_date)->toDateString() : null;
            $finish_time = $this->data['finish_time'] ?? null;

            $startDate = filled($start_date) && filled($start_time)
                ? Carbon::parse("$start_date $start_time")->toDateTimeString()
                : null;
            $finishDate = filled($finish_date) && filled($finish_time)
                ? Carbon::parse("$finish_date $finish_time")->toDateTimeString()
                : null;

            $ppkb_number = $this->data['ppkb_number'] ?? null;

            $rpkro_detail = $rpkro->rpkroDetail()->create(
                [
                    'rkbm_unloading_number' => $this->data['rkbm_unloading_number'] ?? null,
                    'rkbm_loading_number' => $this->data['rkbm_loading_number'] ?? null,
                    'ppkb_number' => $ppkb_number,
                    'komoditi' => $this->data['komoditi'] ?? null,
                    'unloading' => (double)$this->data['unloading'] ?? null,
                    'loading' => (double)$this->data['loading'] ?? null,
                    'start_time' => $startDate,
                    'finish_time' => $finishDate,
                    'initial_meter_code' => (double)$this->data['initial_meter_code'] ?? null,
                    'final_meter_code' => (double)$this->data['final_meter_code'] ?? null,
                ]
            );

            if (!$rpkro_detail) throw new \Exception("Gagal membuat Detail RPKRO");

            $payload = [
                'NomorRpkRo' => $rpkro->rpkro_number ?? '',
                'NomorLayanan' => $ppk->pkk_number ?? '',
                'KodeDermaga' => $ppk->terminal?->dock_code ?? '',
                'TanggalRencana' => $plan_date ?? '',
                'JamRencana' => $plan_time ?? '',
                'rpkroDetail' => [
                    'NomorPkk' => $ppk->pkk_number ?? '',
                    'NomorPPKB' => $ppkb_number ?? '',
                    'NomorRkbmMuat' => '',
                    'NomorRkbmBongkar' => '',
                    'KegiatanBongkar' => (double)$rpkro_detail->unloading,
                    'KegiatanMuat' => (double)$rpkro_detail->loading,
                    'Komoditi' => (double)$rpkro_detail->komoditi ?? '',
                    'NomorGudang' => '',
                    'Keterangan' => $rpkro->note ?? '',
                    'TanggalMulaiTambat' => $start_date,
                    'JamMulaiTambat' => $start_time,
                    'TanggalSelesaiTambat' => $finish_date,
                    'JamSelesaiTambat' => $finish_time,
                    'KadeMeterAwal' => (int)$rpkro_detail->initial_meter_code,
                    'KadeMeterAkhir' => (int)$rpkro_detail->final_meter_code,
                ]
            ];

            $naportnetService = app(InaportnetService::class);
            $responseEntryRPKRO = $naportnetService->entryRpkro($payload);
            if (!$responseEntryRPKRO['success'])
                throw new \Exception($responseEntryRPKRO['message']);

            $entryRPKRO = $responseEntryRPKRO['data'];
            $statusCode = $entryRPKRO['statusCode'];
            $statusMessage = $entryRPKRO['statusMessage'];

            $rpkro->update([
                'status' => $statusCode == '01' ? 'setuju' : 'permintaan'
            ]);

            $rpkro->historyIntegrations()->create(
                [
                    'created_by_id' => Auth::user()?->id,
                    'type' => 'rpkro',
                    'document_number' => $rpkro->rpkro_number,
                    'status' => $statusCode,
                    'description' => $statusMessage,
                ]
            );
            DB::commit();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return Notification::make()
                ->title('Gagal membuat RPKRO')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        Notification::make()
            ->title('Berhasil')
            ->body('Data RPKRO berhasil di simpan!')
            ->success()
            ->send();

        return redirect(RpkroResource::getUrl());
    }
}
