<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpkPanduResource\Pages;
use App\Models\SpkPandu;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SpkPanduResource extends Resource
{
    protected static ?string $model = SpkPandu::class;
    protected static ?string $navigationLabel = 'SPK Pandu';
    protected static ?string $navigationGroup = 'Layanan BUP';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->whereNot('status', 'permintaan')->latest())
            ->columns([
                //
                Tables\Columns\TextColumn::make('No')
                    ->label('NO')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('15px'),
                Tables\Columns\TextColumn::make('pandu.name')
                    ->label('Nama Pandu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_pkk')
                    ->label('No. PKK')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_spk_pandu')
                    ->label('No. SPK Pandu')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_pandu')
                    ->label('Waktu Pandu')
                    ->dateTime('d F Y')
                    ->description(fn ($record): string => Carbon::parse($record?->waktu_pandu)->format('H:i')),
                Tables\Columns\TextColumn::make('status')
                    ->label('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'permintaan' => 'warning',
                        'terkirim'   => 'info',
                        'setuju'     => 'success',
                        'rejected'   => 'danger',
                        'selesai'     => 'success',
                    })
                    ->formatStateUsing(fn ($state) => Str::title($state))
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
//                Tables\Actions\Action::make('edit')
//                    ->label(false)
//                    ->icon('heroicon-o-pencil-square')
//                    ->button()
//                    ->tooltip('Edit')
//                    ->url(fn ($record) => static::getUrl('edit', ['record' => $record])),
                Tables\Actions\Action::make('view')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->label(false)
                    ->tooltip('Detail')
                    ->color('success')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),
                Tables\Actions\Action::make('print')
                    ->button()
                    ->icon('heroicon-o-printer')
                    ->label(false)
                    ->tooltip('Cetak Bukti Layanan Kapal')
                    ->color('pink')
                    ->url(fn ( $record) => '/print/ship-service/' . $record->id),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpkPandus::route('/'),
            'create' => Pages\CreateSpkPandu::route('/create'),
            'edit' => Pages\EditSpkPandu::route('{record}/edit'),
            'view' => Pages\ViewSpkPandu::route('{record}/view'),
        ];
    }
}
