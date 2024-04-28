<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewDevice extends ViewRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('primary')
                ->icon('heroicon-s-cog')
                ->slideOver()
                ->url(DeviceResource::getUrl('edit', ['record' => $this->record])),
            ActionGroup::make([
                Actions\Action::make('devices')
                    ->icon('heroicon-s-cog')
                    ->color('success')
                    ->label('Device List')
                    ->url(DeviceResource::getUrl()),

                Actions\DeleteAction::make(),
            ]),
        ];
    }
}
