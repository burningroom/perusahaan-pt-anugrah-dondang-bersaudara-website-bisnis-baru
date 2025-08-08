<?php

namespace App\Livewire\Print;

use App\Models\Configuration;
use App\Models\Invoice as InvoiceModel;
use Livewire\Component;

class Invoice extends Component
{
    public $invoice, $configuration;

    public function mount($invoice_id) {
        $this->invoice = InvoiceModel::find($invoice_id);
        $this->configuration = Configuration::first();
    }

    public function render()
    {
        return view('livewire.print.invoice')
            ->extends('layouts.pdf')
            ->section('content');
    }
}
