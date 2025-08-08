<?php

namespace App\Filament\Resources\SpkPanduResource\Pages;

use App\Filament\Resources\SpkPanduResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRecords;

class ListSpkPandus extends ManageRecords
{
    protected static string $resource = SpkPanduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah'),
        ];
    }
}
