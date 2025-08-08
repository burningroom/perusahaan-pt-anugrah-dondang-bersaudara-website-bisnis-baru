<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pkk extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function cargo(): HasOne
    {
        return $this->hasOne(Cargo::class, 'pkk_id', 'id');
    }

    public function container(): HasOne
    {
        return $this->hasOne(Container::class, 'pkk_id', 'id');
    }

    public function port(): HasOne
    {
        return $this->hasOne(Port::class, 'pkk_id', 'id');
    }

    public function principal(): HasOne
    {
        return $this->hasOne(Principal::class, 'pkk_id', 'id');
    }

    public function rpkro(): HasOne
    {
        return $this->hasOne(Rpkro::class, 'pkk_id', 'id');
    }

    public function spkPandu(): HasOne
    {
        return $this->hasOne(SpkPandu::class, 'pkk_id', 'id');
    }

    public function rkbm(): HasOne
    {
        return $this->hasOne(Rkbm::class, 'pkk_id', 'id');
    }

    public function document(): HasOne
    {
        return $this->hasOne(Document::class, 'pkk_id', 'id');
    }

    public function passenger(): HasOne
    {
        return $this->hasOne(Passenger::class, 'pkk_id', 'id');
    }

    public function ship(): HasOne
    {
        return $this->hasOne(Ship::class, 'pkk_id', 'id');
    }

    public function terminal(): HasOne
    {
        return $this->hasOne(Terminal::class, 'pkk_id', 'id');
    }

    public function setSpkPandu(): HasOne
    {
        return $this->hasOne(SetSpkPandu::class, 'pkk_id', 'id');
    }
}
