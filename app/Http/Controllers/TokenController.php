<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Http\Resources\TokenResource;
use App\Models\Device;

class TokenController extends Controller
{
    public function __invoke(TokenRequest $request): TokenResource
    {
        $device = Device::query()->updateOrCreate([
            'client_device_code' => $request->client_device_code,
        ], $request->validated());

        if ($device->is_blocked) {
            abort(403, 'Device is blocked.');
        }

        $device->tokens()->delete();

        $device->touch();

        return new TokenResource([
            'token' => $device->createToken($device->device_name)->plainTextToken,
        ]);
    }
}
