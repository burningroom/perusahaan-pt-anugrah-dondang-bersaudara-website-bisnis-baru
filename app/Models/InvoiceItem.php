<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    //
    use HasFactory, HasUuids, SoftDeletes;
    protected $guarded = ['id'];

    public function vesselMaster() {
        return $this->belongsTo(VesselRequest::class,'vessel_master_id','id');
    }

    public function spkpandu() {
        return $this->belongsTo(SpkPandu::class,'spk_pandu_id', 'id');
    }
}
