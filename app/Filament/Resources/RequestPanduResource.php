<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequestPanduResource\Pages;
use App\Filament\Resources\RequestPanduResource\RelationManagers;
use App\Models\RequestArrival;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            'index' => Pages\ListRequestPandus::route('/'),
            'create' => Pages\CreateRequestPandu::route('/create'),
            'edit' => Pages\EditRequestPandu::route('/{record}/edit'),
        ];
    }
}
