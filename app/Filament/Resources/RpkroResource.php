<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RpkroResource\Pages;
use App\Filament\Resources\RpkroResource\RelationManagers;
use App\Models\Rpkro;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class RpkroResource extends Resource
{
    protected static ?string $model = Rpkro::class;
    protected static ?string $navigationLabel = 'Data RPKRO';
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
            ->modifyQueryUsing(fn($query) => $query->latest())
            ->columns([
                //
                Tables\Columns\TextColumn::make('No')
                    ->label('NO')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('15px'),
                Tables\Columns\TextColumn::make('rpkro_number')
                    ->label('No RPKRO')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pkk.pkk_number')
                    ->label('No PKK')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pkk.ship.name')
                    ->label('Nama Kapal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ppk_number')
                    ->label('No PPK')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\ColumnGroup::make('Waktu', [
                    Tables\Columns\TextColumn::make('plan_time')
                        ->label('Rencana')
                        ->dateTime('d F Y')
                        ->description(fn($record): string => Carbon::parse($record?->plan_time)->format('H:i'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('rpkroDetail.start_time')
                        ->label('Mulai')
                        ->dateTime('d F Y')
                        ->description(fn($record): string => Carbon::parse($record?->rpkroDetail?->start_time)->format('H:i'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('rpkroDetail.finish_time')
                        ->label('Selesai')
                        ->dateTime('d F Y')
                        ->description(fn($record): string => Carbon::parse($record?->rpkroDetail?->finish_time)->format('H:i'))
                        ->searchable(),
                ]),
                Tables\Columns\TextColumn::make('status')
                    ->label('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'permintaan' => 'warning',
                        'terkirim' => 'info',
                        'setuju' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn($state) => Str::title($state)),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('edit')
                    ->button()
                    ->icon('heroicon-o-pencil-square')
                    ->label(false)
                    ->tooltip('Edit')
                    ->url(fn($record) => static::getUrl('edit', ['record' => $record])),
                Tables\Actions\Action::make('view')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->label(false)
                    ->tooltip('Detail')
                    ->color('success')
                    ->url(fn($record) => static::getUrl('view', ['record' => $record])),
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
            'index' => Pages\ListRpkros::route('/'),
            'create' => Pages\CreateRpkro::route('/create'),
            'edit' => Pages\EditRpkro::route('/{record}/edit'),
            'view' => Pages\ViewRpkro::route('/{record}'),
        ];
    }
}
