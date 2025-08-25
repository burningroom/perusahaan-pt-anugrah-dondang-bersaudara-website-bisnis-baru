<?php

namespace App\Filament\Resources\ExpenseAccountResource\Pages;

use App\Filament\Resources\ExpenseAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenseAccount extends EditRecord
{
    protected static string $resource = ExpenseAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
