<?php

namespace App\Services;

use App\Models\Log;
use HalilCosdu\LogWeaver\Facades\LogWeaver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LogService
{
    public function log(
        string $description,
        array $content,
        string $logResource = 'system',
        string $level = 'info',
        string $disk = 'local',
        string $directory = 'logs',
        ?array $relation = null
    ): Builder|Model {
        $log = LogWeaver::description($description)
            ->content($content)
            ->relation($relation)
            ->logResource($logResource)
            ->level($level)
            ->disk($disk)
            ->directory($directory)
            ->log();

        return Log::query()->create([
            'device_id' => $relation['device_id'] ?? null,
            'disk' => $disk,
            'level' => $level,
            'log_resource' => $logResource,
            'description' => $description,
            'path' => $log['path'],
        ]);
    }
}
