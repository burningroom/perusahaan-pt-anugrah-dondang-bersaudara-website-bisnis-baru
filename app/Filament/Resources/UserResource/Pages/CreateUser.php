<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Helpers\PhoneHelper;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['phone'] = PhoneHelper::formatNumber($data['phone']);
        $data['password'] = Hash::make($data['password']);
        return $data;
    }

    protected function afterCreate(): void
    {
        DB::beginTransaction();
        try {
            $this->getRecord()->company()->create([
                'name' => $this->data['company']['name'] ?? null,
                'npwp' => $this->data['company']['npwp'] ?? null,
                'sktd' => $this->data['company']['sktd'] ?? null,
                'city' => $this->data['company']['city'] ?? null,
                'address' => $this->data['company']['address'] ?? null,
                'phone' => PhoneHelper::formatNumber($this->data['phone']),
                'email' => $this->data['company']['email'] ?? null,
                'website' => $this->data['company']['website'] ?? null,
            ]);
            DB::commit();
        } catch (\Exception|\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            Log::error('Ada kesalahan saat menambah pengguna');
            Notification::make()
                ->title('Gagal')
                ->body('Ada kesalahan saat menambah pengguna')
                ->danger()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl();
    }
}
