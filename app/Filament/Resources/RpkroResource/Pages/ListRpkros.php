<?php

namespace App\Filament\Resources\RpkroResource\Pages;

use App\Filament\Resources\RpkroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRpkros extends ListRecords
{
    protected static string $resource = RpkroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
