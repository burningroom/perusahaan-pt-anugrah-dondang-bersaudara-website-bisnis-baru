<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rpkro extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'plan_time' => 'datetime',
    ];

    public function pkk():BelongsTo
    {
        return $this->belongsTo(Pkk::class, 'pkk_id', 'id');
    }

    public function ppk(): BelongsTo
    {
        return $this->belongsTo(Ppk::class, 'ppk_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function rpkroDetail(): HasOne
    {
        return $this->hasOne(RpkroDetail::class, 'rpkro_id', 'id');
    }

    public function spkPandu(): HasOne
    {
        return $this->hasOne(SpkPandu::class, 'rpkro_id', 'id');
    }

    public function rkbm(): HasOne
    {
        return $this->hasOne(Rkbm::class, 'rpkro_id', 'id');
    }

    public function historyIntegrations(): HasMany
    {
        return $this->hasMany(HistoryIntegration::class, 'document_id', 'id');
    }
}
