<?php

namespace App\Livewire\Print;

use App\Models\Receipt as ReceiptModel;
use Livewire\Component;

class Receipt extends Component
{
    public $receipt;

    public function mount($receipt_id) {
        $this->receipt = ReceiptModel::findOrFail($receipt_id);
    }

    public function render()
    {
        return view('livewire.print.receipt')
            ->extends('layouts.receipt')
            ->section('content');
    }
}
