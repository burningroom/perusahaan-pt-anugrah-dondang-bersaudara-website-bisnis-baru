<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VesselRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function request_arrival(): BelongsTo
    {
        return $this->belongsTo(RequestArrival::class);
    }

    public function vessel_master(): BelongsTo
    {
        return $this->belongsTo(VesselMaster::class);
    }
}
