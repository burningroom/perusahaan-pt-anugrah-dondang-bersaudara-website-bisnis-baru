<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpkPandu extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_pandu' => 'datetime',
        'waktu_gerak' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($result) {
            $result->setNomorNotaAttribute();
        });
    }

    public function setNomorNotaAttribute(): void
    {
        $count_values = SpkPandu::whereDate('created_at', Carbon::now()->toDateString())->count();

        $this->attributes['nomor_nota'] = Carbon::now()->format('ymd') .'/'. str_pad($count_values + 1, 5, '0', STR_PAD_LEFT);
    }

    public function pandu(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function pkk(): BelongsTo
    {
        return $this->belongsTo(Pkk::class, 'pkk_id', 'id');
    }

    public function rpkro(): BelongsTo
    {
        return $this->belongsTo(Rpkro::class, 'pkk_id', 'id');
    }

    public function panduSchedules(): HasMany
    {
        return $this->hasMany(PanduSchedule::class, 'spk_pandu_id', 'id');
    }
}
