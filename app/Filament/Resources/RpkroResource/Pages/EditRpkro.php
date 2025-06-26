<?php

namespace App\Filament\Resources\RpkroResource\Pages;

use App\Filament\Resources\RpkroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRpkro extends EditRecord
{
    protected static string $resource = RpkroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
