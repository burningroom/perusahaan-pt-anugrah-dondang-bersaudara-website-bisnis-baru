<?php

namespace App\Filament\Resources\ExpenseAccountResource\Pages;

use App\Filament\Resources\ExpenseAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseAccounts extends ListRecords
{
    protected static string $resource = ExpenseAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
