<?php

namespace App\Livewire\Print;

use App\Models\SpkPandu;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipService extends Component
{
    public SpkPandu $spk_pandu;

    public function mount($spk_pandu_id): void
    {
        $spk_pandu = SpkPandu::find($spk_pandu_id);
        if (!$spk_pandu)
            throw new NotFoundHttpException();
        $this->spk_pandu = $spk_pandu;
    }

    public function render()
    {
        return view('livewire.print.ship-service', [
            'spk_pandu' => $this->spk_pandu,
        ])
            ->extends('layouts.pdf-2')
            ->section('content');
    }
}
