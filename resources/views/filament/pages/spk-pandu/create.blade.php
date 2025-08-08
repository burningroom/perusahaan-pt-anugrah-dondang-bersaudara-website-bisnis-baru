<x-filament-panels::page>
    <div class="w-full">
        {{ $this->form }}
    </div>
    <section class="flex justify-end mt-4">
        <div class="space-x-5">
            <x-filament::button type="submit" style="{{ !$data['is_pkk_found'] ? 'display: none;' : '' }}" wire:click="createSPKPandu()">Simpan</x-filament::button>
        </div>
    </section>
</x-filament-panels::page>
