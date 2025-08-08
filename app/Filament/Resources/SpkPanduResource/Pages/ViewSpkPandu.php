<?php

namespace App\Filament\Resources\SpkPanduResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\SpkPanduResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewSpkPandu extends ViewRecord
{
    use InteractsWithRecord;

    protected static string $resource = SpkPanduResource::class;

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

        $this->data['nomor_ppk']           = $this->record?->nomor_ppk;
        $this->data['panjang_kapal']           = $this->record?->panjang_kapal;
        $this->data['lebar_kapal']           = $this->record?->lebar_kapal;
        $this->data['gt_kapal']           = $this->record?->gt_kapal;
        $this->data['no_spk_pandu']           = $this->record?->no_spk_pandu;
        $this->data['user_id']           = $this->record?->user_id;
        $this->data['tanggal_pandu']           = $this->record?->waktu_pandu?->toDateString();
        $this->data['jam_pandu']           = $this->record?->waktu_pandu?->format('H:i');
        $this->data['kapal_pandu']           = $this->record?->kapal_pandu;
        $this->data['kapal_tunda']           = $this->record?->kapal_tunda;
        $this->data['lokasi_awal']           = $this->record?->lokasi_awal;
        $this->data['lokasi_akhir']           = $this->record?->lokasi_akhir;
        $this->data['jenis_pandu']           = $this->record?->jenis_pandu;
        $this->data['keperluan']           = $this->record?->keperluan;
        $this->data['waktu_gerak']           = $this->record?->waktu_gerak?->format('Y-m-d H:i');
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
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
                                    ->options(fn () => User::whereHas('roles', fn ($query) => $query->whereIn('name', ['pandu']))->pluck('name', 'id')->toArray())
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
                                    // ->format('H:i')
                                    // ->displayFormat('H:i')
                                    ->native()
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
                                    ->columnSpan(2),
                                TextInput::make('kapal_tunda')
                                    ->label('Kapal Tunda')
                                    ->minValue(0)
                                    ->maxValue(4)
                                    ->required()
                                    ->numeric()
                                    ->suffix('Kapal')
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
                                        'sungai'     => 'Sungai',
                                        'laut'       => 'Laut',
                                        'bandar'     => 'Bandar',
                                        'luar-biasa' => 'Luar Biasa',
                                    ])
                                    ->native(false)
                                    ->columnSpan(4),
                                Select::make('keperluan')
                                    ->label('Keperluan')
                                    ->options([
                                        'masuk'  => 'Masuk',
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
                                    ->displayFormat('d F Y H:i')
                                    ->native(false)
                                    ->seconds(false)
                                    ->columnSpan(4),
                            ]),
                    ])
                // ->hidden(fn (Get $get) => !$get('is_pkk_found')),
            ])->statePath('data');
    }
}
