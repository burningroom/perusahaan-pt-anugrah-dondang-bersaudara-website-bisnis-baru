<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoapLog extends Model
{
    use HasFactory;
    public $incrementing = false;     // using UUID
    protected $keyType = 'string';
    protected $guarded = [];
    protected $casts = [
        'headers'  => 'array',
        'is_wsdl'  => 'boolean',
    ];
}
