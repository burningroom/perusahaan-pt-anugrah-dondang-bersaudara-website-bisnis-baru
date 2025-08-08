<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Helpers\PhoneHelper;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['phone'] = PhoneHelper::formatNumber($data['phone']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        $record->company()->updateOrCreate(
            [
                'user_id' => $record->id,
            ],
            [
                'name' => $data['company']['name'] ?? null,
                'npwp' => $data['company']['npwp'] ?? null,
                'sktd' => $data['company']['sktd'] ?? null,
                'city' => $data['company']['city'] ?? null,
                'address' => $data['company']['address'] ?? null,
                'phone' => PhoneHelper::formatNumber($data['company']['phone']),
                'email' => $data['company']['email'] ?? null,
                'website' => $data['company']['website'] ?? null,
            ]);

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['company']['name'] = $this->getRecord()?->company?->name;
        $data['company']['npwp'] = $this->getRecord()?->company?->npwp;
        $data['company']['sktd'] = $this->getRecord()?->company?->sktd;
        $data['company']['city'] = $this->getRecord()?->company?->city;
        $data['company']['address'] = $this->getRecord()?->company?->address;
        $data['company']['phone'] = $this->getRecord()?->company?->phone;
        $data['company']['email'] = $this->getRecord()?->company?->email;
        $data['company']['website'] = $this->getRecord()?->company?->website;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }
}
