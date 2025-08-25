<?php

namespace App\Filament\Resources\RkbmResource\Pages;

use App\Filament\Resources\RkbmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRkbm extends EditRecord
{
    protected static string $resource = RkbmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
