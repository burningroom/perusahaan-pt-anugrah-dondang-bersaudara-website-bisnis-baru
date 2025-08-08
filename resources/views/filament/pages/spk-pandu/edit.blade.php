<x-filament-panels::page>
    <div class="w-full">
        {{ $this->form }}
    </div>
    <section class="flex justify-end">
        <div class="space-x-5">
            <x-filament::button type="submit" wire:click="editSPKPandu()">Simpan</x-filament::button>
        </div>
    </section>
</x-filament-panels::page>
