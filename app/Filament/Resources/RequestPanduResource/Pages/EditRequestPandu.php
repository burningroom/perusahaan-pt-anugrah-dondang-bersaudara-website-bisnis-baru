<?php

namespace App\Filament\Resources\RequestPanduResource\Pages;

use App\Filament\Resources\RequestPanduResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestPandu extends EditRecord
{
    protected static string $resource = RequestPanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
