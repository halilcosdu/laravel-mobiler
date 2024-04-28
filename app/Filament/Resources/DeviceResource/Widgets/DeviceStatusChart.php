<?php

namespace App\Filament\Resources\DeviceResource\Widgets;

use App\Filament\Resources\DeviceResource\Pages\ListDevices;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DeviceStatusChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Devices';

    protected static ?string $maxHeight = '200px';

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'week';

    protected function getData(): array
    {
        $filter = $this->filter;

        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];

        match ($filter) {
            'week' => $data = Trend::query($query)
                ->between(
                    start: now()->subWeek(),
                    end: now(),
                )
                ->perDay()
                ->count(),
            'month' => $data = Trend::query($query)
                ->between(
                    start: now()->subMonth(),
                    end: now(),
                )
                ->perDay()
                ->count(),
            '3months' => $data = Trend::query($query)
                ->between(
                    start: now()->subMonth(3),
                    end: now(),
                )
                ->perDay()
                ->count(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Devices',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    public function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            '3months' => 'Last 3 Months',
        ];
    }

    public function getTablePage(): string
    {
        return ListDevices::class;
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
