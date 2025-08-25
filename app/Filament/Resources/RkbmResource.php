<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RkbmResource\Pages;
use App\Filament\Resources\RkbmResource\RelationManagers;
use App\Models\Rkbm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RkbmResource extends Resource
{
    protected static ?string $model = Rkbm::class;
    protected static ?string $navigationLabel = 'Data RKBM';
    protected static ?string $navigationGroup = 'Layanan PBM';
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
            'index' => Pages\ListRkbms::route('/'),
            'create' => Pages\CreateRkbm::route('/create'),
            'edit' => Pages\EditRkbm::route('/{record}/edit'),
        ];
    }
}
