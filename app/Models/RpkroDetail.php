<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RpkroDetail extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'start_time'  => 'datetime',
        'finish_time' => 'datetime',
    ];

    public function rpkro(): BelongsTo
    {
        return $this->belongsTo(Rpkro::class, 'rpkro_id', 'id');
    }
}
