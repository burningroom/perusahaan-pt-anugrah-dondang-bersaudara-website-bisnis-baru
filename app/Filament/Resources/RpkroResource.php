<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RpkroResource\Pages;
use App\Filament\Resources\RpkroResource\RelationManagers;
use App\Models\Rpkro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRpkros::route('/'),
            'create' => Pages\CreateRpkro::route('/create'),
            'edit' => Pages\EditRpkro::route('/{record}/edit'),
        ];
    }
}
