<?php

namespace App\Filament\Resources\DeviceResource\Widgets;

use App\Filament\Resources\DeviceResource\Pages\ListDevices;
use App\Models\Device;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DeviceStatusOverviewChart extends BaseWidget
{
    protected static ?string $heading = 'Stats Chart';

    protected static ?string $pollingInterval = null;

    public function getTablePage(): string
    {
        return ListDevices::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Device', Device::query()->count())
                ->description('Device count')
                ->color('success')
                ->icon('heroicon-s-device-phone-mobile')
                ->chart($this->getChartData()),

            Stat::make('Premium Device', Device::query()->whereIsPremium(true)->count())
                ->description('Premium device count')
                ->color('primary')
                ->icon('heroicon-o-currency-dollar')
                ->chart($this->getChartData(['is_premium' => true])),

            Stat::make('Blocked Device', Device::query()->whereIsBlocked(true)->count())
                ->description('Blocked device count')
                ->color('danger')
                ->icon('heroicon-m-no-symbol')
                ->chart($this->getChartData(['is_blocked' => true])),
        ];
    }

    public function getChartData($query = []): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $dates = range(0, $end->diffInDays($start));
        $deviceCounts = array_map(function ($days) use ($start, $query) {
            $date = $start->copy()->addDays($days);
            $dateString = $date->format('Y-m-d');
            $deviceCountQuery = Device::query()
                ->whereDate('created_at', $date);
            if (! empty($query) && is_array($query)) {
                $deviceCountQuery->where($query);
            }

            return [$dateString => $deviceCountQuery->count()];
        }, $dates);

        return array_merge(...$deviceCounts);
    }
}
