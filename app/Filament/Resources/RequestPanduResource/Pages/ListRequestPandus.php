<?php

namespace App\Filament\Resources\RequestPanduResource\Pages;

use App\Filament\Resources\RequestPanduResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRequestPandus extends ListRecords
{
    protected static string $resource = RequestPanduResource::class;
    protected static ?string $title = 'Data Permintaan Pandu';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['vessel_tb'] = 'TB';
        return $data;
    }
}
