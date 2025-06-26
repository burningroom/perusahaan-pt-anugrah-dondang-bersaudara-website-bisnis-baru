<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class RequestArrival extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_pengolongan' => 'datetime'
    ];

    public static function validator()
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
}
