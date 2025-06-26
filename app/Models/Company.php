<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pkks(): HasMany
    {
        return $this->hasMany(Pkk::class, 'company_id');
    }

    public function principals(): HasMany
    {
        return $this->hasMany(Principal::class, 'company_id', 'id');
    }

    public function rpkros(): HasMany
    {
        return $this->hasMany(Rpkro::class, 'company_id', 'id');
    }

    public function setSpkPandus(): HasMany
    {
        return $this->hasMany(SetSpkPandu::class, 'company_id', 'id');
    }

    public function rkbms(): HasMany
    {
        return $this->hasMany(Rkbm::class, 'company_id', 'id');
    }
}
