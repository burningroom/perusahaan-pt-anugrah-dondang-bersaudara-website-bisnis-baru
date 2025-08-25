<?php

namespace App\Filament\Resources\RkbmResource\Pages;

use App\Filament\Resources\RkbmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRkbms extends ListRecords
{
    protected static string $resource = RkbmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
