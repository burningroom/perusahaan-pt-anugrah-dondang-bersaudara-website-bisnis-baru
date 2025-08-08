<?php

namespace App\Filament\Resources\RequestPanduResource\Pages;

use App\Filament\Resources\RequestPanduResource;
use App\Filament\Resources\RpkroResource;
use App\Models\RequestArrival;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditRequestPandu extends EditRecord
{
    protected static string $resource = RequestPanduResource::class;

    protected ?string $heading = 'Informasi Data Permintaan Pandu';

    protected static ?string $title = 'Informasi Data Permintaan Pandu';

    protected function getHeaderActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        RequestArrival::find($data['id'])->update([
            'status' => 'proses'
        ]);

        $data['nama_perusahaan'] = $this->getRecord()?->pkk?->principal?->company_name;
        $data['npwp'] = $this->getRecord()?->pkk?->principal?->npwp;
        $data['tanda_pendaftaran_kapal'] = $this->getRecord()?->pkk?->ship?->registration_number;
        $data['nahkoda'] = $this->getRecord()?->pkk?->ship?->captain_name;
        $data['drt'] = $this->getRecord()?->pkk?->ship?->drt;
        $data['grt'] = $this->getRecord()?->pkk?->ship?->grt;
        $data['loa'] = $this->getRecord()?->pkk?->ship?->loa;
        $data['jenis_kapal'] = $this->getRecord()?->pkk?->ship?->ship_type;
        $data['tahun_pembuatan'] = $this->getRecord()?->pkk?->ship?->year_build;
        $data['lebar_kapal'] = $this->getRecord()?->pkk?->ship?->width;
        $data['draft_max'] = $this->getRecord()?->pkk?->ship?->max_draft;
        $data['draft_depan'] = $this->getRecord()?->pkk?->ship?->front_draft;
        $data['draft_belakang'] = $this->getRecord()?->pkk?->ship?->rear_draft;
        $data['draft_tengah'] = $this->getRecord()?->pkk?->ship?->midship_draft;
        $data['jenis_trayek'] = $this->getRecord()?->pkk?->route_type;
        $data['bendera'] = $this->getRecord()?->pkk?->ship?->flag;
        $data['call_sign'] = $this->getRecord()?->pkk?->ship?->call_sign;
        $data['imo_number'] = $this->getRecord()?->pkk?->ship?->imo_number;
        $data['tanggal_eta'] = $this->getRecord()?->pkk?->tanggalEta;
        $data['tanggal_etd'] = $this->getRecord()?->pkk?->tanggalEtd;
        $data['kode_pelabuhan_asal'] = $this->getRecord()?->pkk?->port?->port_origin_code;
        $data['pelabuhan_asal'] = $this->getRecord()?->pkk?->port?->origin_port;
        $data['kode_pelabuhan_tujuan'] = $this->getRecord()?->pkk?->port?->destination_port_code;
        $data['pelabuhan_tujuan'] = $this->getRecord()?->pkk?->port?->destination_port_name;
        $data['status_but'] = $this->getRecord()?->pkk?->status_but;
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            DB::beginTransaction();
            $pkk = $record->pkk;
            if (!$pkk) throw new \Exception("Pengajuan tidak mempunyai PKK");

            $pkk->tanggalEta = $data['tanggal_eta'];
            $pkk->tanggalEtd = $data['tanggal_etd'];
            $pkk->status_but = $data['status_but'];
            $pkk->save();

            $record->update([
                'status' => 'setuju'
            ]);
            $principal = $pkk->principal;
            $principal->updateOrCreate([
                'pkk_id' => $pkk->id,
            ], [
                'company_name' => $data['nama_perusahaan'],
                'npwp' => $data['npwp']
            ]);

            $ship = $pkk->ship;
            $ship->updateOrCreate([
                'pkk_id' => $pkk->id,
            ], [
                'registration_number' => $data['tanda_pendaftaran_kapal'],
                'captain_name' => $data['nahkoda'],
                'drt' => $data['drt'],
                'grt' => $data['grt'],
                'loa' => $data['loa'],
                'jenis_kapal' => $data['jenis_kapal'],
                'tahun_pembuatan' => $data['tahun_pembuatan'],
                'lebar_kapal' => $data['lebar_kapal'],
                'draft_max' => $data['draft_max'],
                'draft_depan' => $data['draft_depan'],
                'draft_belakang' => $data['draft_belakang'],
                'draft_tengah' => $data['draft_tengah'],
                'jenis_trayek' => $data['jenis_trayek'],
                'bendera' => $data['bendera'],
                'call_sign' => $data['call_sign'],
                'imo_number' => $data['imo_number'],
            ]);

            $port = $pkk->port;
            $port->updateOrCreate([
                'pkk_id' => $pkk->id
            ], [
                'kode_pelabuhan_asal' => $data['kode_pelabuhan_asal'],
                'pelabuhan_asal' => $data['pelabuhan_asal'],
                'kode_pelabuhan_tujuan' => $data['kode_pelabuhan_tujuan'],
                'pelabuhan_tujuan' => $data['pelabuhan_tujuan'],
            ]);
            DB::commit();
        } catch (\Exception|\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            Log::error("Ada kesalahan saat merubah status proses setuju pandu");
            Notification::make()
                ->danger()
                ->title('Gagal menyimpan data pandu')
                ->body($th->getMessage())
                ->send();
            $this->halt();
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return RpkroResource::getUrl().'/create';
    }
}
