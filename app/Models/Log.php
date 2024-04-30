<?php

namespace App\Models;

use HalilCosdu\LogWeaver\Facades\LogWeaver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    protected function path(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                return LogWeaver::disk($this->disk)->get($value);
            },
        );
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
