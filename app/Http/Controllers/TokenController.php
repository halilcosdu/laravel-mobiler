<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Http\Resources\TokenResource;
use App\Models\Device;
use App\Services\LogService;

class TokenController extends Controller
{
    public function __invoke(TokenRequest $request, LogService $logService): TokenResource
    {
        $device = Device::query()
            ->updateOrCreate(
                [
                    'client_device_code' => $request->client_device_code,
                ],
                $request->validated()
            );

        if ($device->is_blocked) {
            $logService->log(description: 'Device is blocked.', content: $device->toArray(), level: 'critical', relation: ['device_id' => $device->id]);
            abort(403, 'Device is blocked.');
        }

        $device->tokens()->delete();

        $device->touch();

        $logService->log(description: 'Device token created.', content: $device->toArray(), relation: ['device_id' => $device->id]);

        return new TokenResource([
            'token' => $device->createToken($device->device_name)->plainTextToken,
        ]);
    }
}
