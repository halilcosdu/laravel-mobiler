<?php

namespace App\Filament\Resources\LogResource\Pages;

use App\Filament\Resources\LogResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListLogs extends ListRecords
{
    protected static string $resource = LogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->icon('heroicon-m-home'),
            'event' => Tab::make('Event')
                ->icon('heroicon-s-bolt')
                ->modifyQueryUsing(function ($query) {
                    $query->where('log_resource', 'event');
                }),
            'system' => Tab::make('System')
                ->icon('heroicon-m-rectangle-stack')
                ->modifyQueryUsing(function ($query) {
                    $query->where('log_resource', 'system');
                }),
            'critical' => Tab::make('Critical')
                ->icon('heroicon-c-hand-raised')
                ->modifyQueryUsing(function ($query) {
                    $query->where('level', 'critical');
                }),
        ];
    }
}
