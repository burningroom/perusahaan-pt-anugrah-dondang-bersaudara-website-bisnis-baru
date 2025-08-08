<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    //
    use HasFactory, HasUuids, SoftDeletes;
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function invoiceStatus() {
        return $this->belongsTo(InvoiceStatus::class);
    }

    public function invoiceStatusLatest() {
        return $this->hasOne(InvoiceStatus::class,'invoice_id','id')->latest();
    }

    public function invoiceItems() {
        return $this->hasMany(InvoiceItem::class);
    }

    public function receipts() {
        return $this->hasMany(Receipt::class);
    }
}
