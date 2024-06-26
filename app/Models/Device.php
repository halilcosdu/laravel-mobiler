<?php

namespace App\Models;

use App\Enums\OSTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Device extends Model
{
    use HasApiTokens, HasFactory;

    protected $casts = [
        'os_type' => OSTypes::class,
        'is_blocked' => 'boolean',
        'is_premium' => 'boolean',
    ];
}
