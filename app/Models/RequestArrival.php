<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class RequestArrival extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_pengolongan' => 'datetime'
    ];

    protected $appends = [
        'vessel_tb',
        'vessel_bg',
    ];

    public static function validator(): array
    {
        return [
            'nomor_pkk' => ['required', 'string'],
            'vessel_tb' => ['required', 'string'],
            'vessel_bg' => ['nullable', 'string'],
            'rkbm_loading_number' => ['nullable', 'string'],
            'rkbm_unloading_number' => ['nullable', 'string'],
            'loading_type' => ['nullable', 'string'],
            'loading' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
            'waktu' => ['required', 'date_format:H:i'],
            'jenis_pengolongan' => ['required', 'string', Rule::in(['masuk', 'keluar', 'pindah'])],
            'lokasi_awal' => ['required', 'string'],
            'lokasi_akhir' => ['required', 'string'],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pkk(): MorphOne
    {
        return $this->morphOne(Pkk::class, 'requestable');
    }

    public function vessel_requests(): HasMany
    {
        return $this->hasMany(VesselRequest::class);
    }

    public function getVesselTbAttribute(): ?VesselMaster
    {
        $tbRequest = $this->vessel_requests
            ->first(function ($vesselRequest) {
                return $vesselRequest->vessel_master?->type === 'TB';
            });

        return $tbRequest?->vessel_master;
    }

    public function getVesselBgAttribute(): ?VesselMaster
    {
        $bgRequest = $this->vessel_requests
            ->first(function ($vesselRequest) {
                return $vesselRequest->vessel_master?->type === 'BG';
            });

        return $bgRequest?->vessel_master;
    }

    public function company_request(): HasOne
    {
        return $this->hasOne(CompanyRequest::class, 'request_arrival_id', 'id');
    }
}
