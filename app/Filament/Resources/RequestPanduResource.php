<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestPanduResource\Pages;
use App\Models\RequestArrival;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RequestPanduResource extends Resource
{
    protected static ?string $model = RequestArrival::class;
    protected static ?string $navigationLabel = 'Permintaan Pandu';
    protected static ?string $navigationGroup = 'Layanan BUP';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->heading('Permintaan Kedatangan Kapal')
                    ->columns(12)
                    ->schema([
                        // ...
                        TextInput::make('vessel_tb.name')
                            ->label('Nama Kapal TB')
                            ->required()
                            ->disabled()
                            ->columnSpan(4),
                        TextInput::make('nomor_pkk')
                            ->label('Nomor PKK')
                            ->required()
                            ->disabled()
                            ->columnSpan(4),
                        DateTimePicker::make('waktu_pengolongan')
                            ->label('Waktu Penggolongan')
                            ->format('Y-m-d H:i')
                            ->displayFormat('d m Y H:i')
                            ->seconds(false)
                            ->required()
                            ->disabled()
                            ->columnSpan(4),
                        TextInput::make('jenis_pengolongan')
                            ->label('Jenis Pengolongan  ')
                            ->disabled()
                            ->columnSpan(4),
                        TextInput::make('lokasi_awal')
                            ->label('Lokasi Awal')
                            ->disabled()
                            ->columnSpan(4),
                        TextInput::make('lokasi_akhir')
                            ->label('Lokasi Akhir')
                            ->disabled()
                            ->columnSpan(4),
                    ]),
                Section::make()
                    ->heading('Data Kedatangan Kapal')
                    ->columns(12)
                    ->schema([
                        Fieldset::make('Data Perusahaan')
                            ->schema([
                                TextInput::make('nama_perusahaan')
                                    ->label('Nama Perusahaan'),
                                TextInput::make('npwp')
                                    ->label('NPWP')
                            ]),
                        Fieldset::make('Data kapal')
                            ->columns(12)
                            ->schema([
                                TextInput::make('nomor_pkk')
                                    ->required()
                                    ->disabled()
                                    ->columnSpan(4),
                                TextInput::make('tanda_pendaftaran_kapal')
                                    ->label('Tanda Pendaftaran Kapal')
                                    ->columnSpan(4),
                                TextInput::make('nahkoda')
                                    ->label('Nahkoda')
                                    ->columnSpan(4),
                                TextInput::make('drt')
                                    ->label('DRT')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2),
                                TextInput::make('grt')
                                    ->label('GRT')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2),
                                TextInput::make('loa')
                                    ->label('LOA')
                                    ->numeric()
                                    ->minValue(0)
                                    ->columnSpan(2),
                                TextInput::make('jenis_kapal')
                                    ->label('Jenis Kapal')
                                    ->columnSpan(2),
                                TextInput::make('tahun_pembuatan')
                                    ->label('Tahun Pembuatan')
                                    ->minValue(0)
                                    ->numeric()
                                    ->columnSpan(2),
                                TextInput::make('lebar_kapal')
                                    ->label('Lebar Kapal')
                                    ->minValue(0)
                                    ->numeric()
                                    ->columnSpan(2),
                                TextInput::make('draft_max')
                                    ->label('Draft Max')
                                    ->minValue(0)
                                    ->numeric()
                                    ->columnSpan(3),
                                TextInput::make('draft_depan')
                                    ->label('Draft Depan')
                                    ->minValue(0)
                                    ->numeric()
                                    ->columnSpan(3),
                                TextInput::make('draft_belakang')
                                    ->label('Draft Belakang')
                                    ->minValue(0)
                                    ->numeric()
                                    ->columnSpan(3),
                                TextInput::make('draft_tengah')
                                    ->label('Draft Tengah')
                                    ->minValue(0)
                                    ->numeric()
                                    ->columnSpan(3),
                                TextInput::make('jenis_trayek')

                                    ->label('Jenis Trayek')
                                    ->columnSpan(3),
                                TextInput::make('bendera')
                                    ->label('Bendera')
                                    ->columnSpan(3),
                                TextInput::make('call_sign')
                                    ->label('Call Sign')
                                    ->columnSpan(3),
                                TextInput::make('imo_number')
                                    ->label('Imo Number')
                                    ->columnSpan(3),
                            ]),
                        Fieldset::make('Data Rute')
                            ->columns(12)
                            ->schema([
                                DateTimePicker::make('tanggal_eta')
                                    ->label('Tanggal ETA')
                                    ->columnSpan(3),
                                DateTimePicker::make('tanggal_etd')
                                    ->label('Tanggal ETD')
                                    ->columnSpan(3),
                                TextInput::make('kode_pelabuhan_asal')
                                    ->label('Kode Pelabuhan Asal')
                                    ->columnSpan(3),
                                TextInput::make('pelabuhan_asal')
                                    ->label('Pelabuhan Asal')
                                    ->columnSpan(3),
                                TextInput::make('kode_pelabuhan_tujuan')
                                    ->label('Kode Pelabuhan Tujuan')
                                    ->columnSpan(4),
                                TextInput::make('pelabuhan_tujuan')
                                    ->label('Pelabuhan Tujuan')
                                    ->columnSpan(4),
                                Select::make('status_but')
                                    ->label('Status BUT')
                                    ->options([
                                        'A' => "Aktif",
                                        'N' => "Non Aktif",
                                    ])
                                    ->native(false)
                                    ->columnSpan(4),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->columns([
                //
                TextColumn::make('No')
                    ->label('NO')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('15px'),
                TextColumn::make('user.name')
                    ->label('Nama Agent')
                    ->searchable(),
                TextColumn::make('vessel_tb.name')
                    ->label('Nama Kapal TB')
                    ->searchable(),
                TextColumn::make('vessel_bg.name')
                    ->label('Nama Kapal BG')
                    ->searchable(),
                TextColumn::make('nomor_pkk')
                    ->label('Nomor PKK')
                    ->copyable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('waktu_pengolongan')
                    ->label('Waktu Pengolongan')
                    ->dateTime('d F Y')
                    ->description(fn($record): string => Carbon::parse($record?->waktu_pengolongan)->format('H:i'))
                    ->searchable(),
                TextColumn::make('jenis_pengolongan')
                    ->label('Jenis Pengolongan')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                        'pindah' => 'warning',
                    })
                    ->formatStateUsing(fn($state) => Str::title($state))
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'permintaan' => 'warning',
                        'proses' => 'info',
                        'setuju' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn($state) => Str::title($state))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->label('Proses Permintaan')
                    ->hidden(fn($record) => $record?->status === 'setuju'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequestPandus::route('/'),
            'create' => Pages\CreateRequestPandu::route('/create'),
            'edit' => Pages\EditRequestPandu::route('/{record}/edit'),
        ];
    }
}
