<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptItem extends Model
{
    //
    use HasFactory, HasUuids, SoftDeletes;
    protected $guarded = ['id'];

    public function receipt(): BelongsTo {
        return $this->belongsTo(Receipt::class);
    }

    public function invoice():BelongsTo {
        return $this->belongsTo(Invoice::class);
    }
}
