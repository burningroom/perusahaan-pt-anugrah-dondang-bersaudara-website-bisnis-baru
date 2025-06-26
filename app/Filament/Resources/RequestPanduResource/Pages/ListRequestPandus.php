<?php

namespace App\Filament\Resources\RequestPanduResource\Pages;

use App\Filament\Resources\RequestPanduResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestPandus extends ListRecords
{
    protected static string $resource = RequestPanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
