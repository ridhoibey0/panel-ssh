<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'tunnel' => 'array',
        'banner' => 'array',
        'cloudflare' => 'array',
        'total_accounts' => 'array',
        'api_price' => 'array'
    ];
}
