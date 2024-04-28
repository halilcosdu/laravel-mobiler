<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use App\Filament\Resources\DeviceResource\Widgets\DeviceStatusChart;
use App\Filament\Resources\DeviceResource\Widgets\DeviceStatusOverviewChart;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListDevices extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            DeviceStatusOverviewChart::class,
            DeviceStatusChart::class,
        ];
    }
}
