<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseAccountResource\Pages;
use App\Filament\Resources\ExpenseAccountResource\RelationManagers;
use App\Models\ExpenseAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseAccountResource extends Resource
{
    protected static ?string $model = ExpenseAccount::class;
    protected static ?string $navigationLabel = 'Akun Biaya';
    protected static ?string $navigationGroup = 'Finance';
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
            'index' => Pages\ListExpenseAccounts::route('/'),
            'create' => Pages\CreateExpenseAccount::route('/create'),
            'edit' => Pages\EditExpenseAccount::route('/{record}/edit'),
        ];
    }
}
