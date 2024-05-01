<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class SubscriptionController extends Controller
{
    public function webhook(Request $request): JsonResponse
    {
        $this->checkAndAuthorize($request);
        $event = $request->event;
        $type = $event['type'];

        $handlers = [
            'SUBSCRIBER_ALIAS' => 'handleDefault',
            'TEST' => 'handleDefault',
            'RENEWAL' => 'handleRenewal',
            'UNCANCELLATION' => 'handleRenewal',
            'NON_RENEWING_PURCHASE' => 'handleRenewal',
            'PRODUCT_CHANGE' => 'handleRenewal',
            'INITIAL_PURCHASE' => 'handleRenewal',
            'CANCELLATION' => 'handleCancellation',
            'EXPIRATION' => 'handleExpiration',
            'BILLING_ISSUE' => 'handleExpiration',
            'SUBSCRIPTION_PAUSED' => 'handleExpiration',
            'TRANSFER' => 'handleTransfer',
        ];

        $handler = $handlers[$type] ?? 'handleDefault';

        return $this->$handler($event);
    }

    private function handleDefault($event): JsonResponse
    {
        return response()->json();
    }

    private function handleRenewal($event): JsonResponse
    {
        $device = $this->getDevice($event['app_user_id']);
        $this->updateDevicePremiumStatus($device, true);

        return response()->json();
    }

    private function handleCancellation($event): JsonResponse
    {
        return response()->json();
    }

    private function handleExpiration($event): JsonResponse
    {
        $device = $this->getDevice($event['app_user_id']);
        $this->updateDevicePremiumStatus($device, false);

        return response()->json();
    }

    private function handleTransfer($event): JsonResponse
    {
        $oldClientDeviceCode = $event['transferred_from'][0];
        $clientDeviceCode = $event['transferred_to'][0];
        $oldDevice = $this->getDevice($oldClientDeviceCode);
        if (! $oldDevice->is_premium) {
            return response()->json();
        }
        $this->updateDevicePremiumStatus($oldDevice, false);
        $device = $this->getDevice($clientDeviceCode);
        $this->updateDevicePremiumStatus($device, true);

        return response()->json();
    }

    private function getDevice($clientDeviceCode): Model|Device|Builder
    {
        return Device::query()->where('client_device_code', $clientDeviceCode)->firstOrFail();
    }

    private function updateDevicePremiumStatus($device, $isPremium): void
    {
        $device->is_premium = $isPremium;
        $device->save();
    }

    private function checkAndAuthorize($request): void
    {
        if ($request->bearerToken() != config('services.revenuecat.token')) {
            throw new UnauthorizedException('Token invalid.');
        }

    }

    private function getPackage($productPackage): string
    {
        $packages = ['weekly', 'monthly', 'yearly'];

        foreach ($packages as $package) {
            if (str_contains($productPackage, $package)) {
                return $package;
            }
        }

        return $productPackage;
    }
}
