<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rkbm extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_tiba'         => 'date',
        'tanggal_berangkat'    => 'date',
        'tanggal_rencana_muat' => 'date',
        'tanggal_mulai_muat'   => 'date',
        'tanggal_selesai_muat' => 'date',
    ];

    public function pkk()
    {
        return $this->belongsTo(Pkk::class, 'pkk_id', 'id');
    }

    public function rpkro()
    {
        return $this->belongsTo(Rpkro::class, 'rpkro_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function cargoItems(): HasMany
    {
        return $this->hasMany(CargoItem::class, 'rkbm_id', 'id');
    }

    public function document(): HasOne
    {
        return $this->hasOne(Document::class, 'rkbm_id', 'id');
    }
}
