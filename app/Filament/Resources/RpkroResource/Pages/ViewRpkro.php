<?php

namespace App\Filament\Resources\RpkroResource\Pages;

use App\Filament\Resources\RpkroResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\ViewRecord;

class ViewRpkro extends ViewRecord
{
    use InteractsWithRecord;

    protected static string $resource = RpkroResource::class;

    protected static ?string $title = 'Detail Data RPKRO';

    protected ?string $heading = 'Detail Data RPKRO';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->data['nomorPKK']              = $this->record?->pkk?->pkk_number;
        $this->data['kapalNama']             = $this->record?->pkk?->ship?->name;
        $this->data['tanggalEta']            = $this->record?->pkk?->tanggalEta;
        $this->data['perusahaanNama']        = $this->record?->pkk?->principal?->company_name;
        $this->data['dermagaKode']           = $this->record?->pkk?->terminal?->dock_code;
        $this->data['dermagaNama']           = $this->record?->pkk?->terminal?->dock_name;

        $this->data['rpkro_number'] = $this->record?->pkk?->rpkro?->rpkro_number;
        $this->data['rpkro_type'] = $this->record?->pkk?->rpkro?->rpkro_type;
        $this->data['plan_date'] = $this->record?->pkk?->rpkro?->plan_time?->toDateString();
        $this->data['plan_time'] = $this->record?->pkk?->rpkro?->plan_time?->format('H:i');
        $this->data['destination_port_name'] = $this->record?->pkk?->port?->destination_port_name;
        $this->data['note'] = $this->record?->pkk?->rpkro?->note;

        $this->data['rkbm_unloading_number'] = $this->record?->pkk?->rpkro?->rpkroDetail?->rkbm_unloading_number;
        $this->data['rkbm_loading_number'] = $this->record?->pkk?->rpkro?->rpkroDetail?->rkbm_loading_number;
        $this->data['ppkb_number'] = $this->record?->pkk?->rpkro?->rpkroDetail?->ppkb_number;
        $this->data['komoditi'] = $this->record?->pkk?->rpkro?->rpkroDetail?->komoditi;
        $this->data['unloading'] = $this->record?->pkk?->rpkro?->rpkroDetail?->unloading;
        $this->data['loading'] = $this->record?->pkk?->rpkro?->rpkroDetail?->loading;
        $this->data['start_date'] = $this->record?->pkk?->rpkro?->rpkroDetail?->start_time?->toDateString();
        $this->data['start_time'] = $this->record?->pkk?->rpkro?->rpkroDetail?->start_time?->format('H:i');
        $this->data['finish_date'] = $this->record?->pkk?->rpkro?->rpkroDetail?->finish_time?->toDateString();
        $this->data['finish_time'] = $this->record?->pkk?->rpkro?->rpkroDetail?->finish_time?->format('H:i');
        $this->data['initial_meter_code'] = $this->record?->pkk?->rpkro?->rpkroDetail?->initial_meter_code;
        $this->data['final_meter_code'] = $this->record?->pkk?->rpkro?->rpkroDetail?->final_meter_code;
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
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
                    ]),
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
                                        'masuk'      => 'Masuk',
                                        'pindah'     => 'Pindah',
                                        'keluar'     => 'Keluar',
                                        'perpanjang' => 'Perpanjang',
                                    ])
                                    // ->native(false)
                                    ->required()
                                    ->columnSpan(6),
                                DatePicker::make('plan_date')
                                    ->label('Tanggal Rencana')
                                    // ->format('d/m/Y')
                                    ->displayFormat('d F Y')
                                    ->required()
                                    // ->native(false)
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
                                    // ->displayFormat('d F Y')
                                    // ->native(false)
                                    ->required()
                                    ->columnSpan(2),
                                TimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->required()
                                    ->seconds(false)
                                    ->columnSpan(2),
                                DatePicker::make('finish_date')
                                    ->label('Tanggal Selesai')
                                    // ->format('Y-m-d')
                                    // ->displayFormat('d F Y')
                                    // ->native(false)
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
            ])->statePath('data');
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Infolists\Components\TextEntry::make('name'),
    //             Infolists\Components\TextEntry::make('email'),
    //             Infolists\Components\TextEntry::make('notes')
    //                 ->columnSpanFull(),
    //         ]);
    // }
}
