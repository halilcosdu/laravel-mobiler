<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            TicketResource\Widgets\TicketStatsChart::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Tickets')->icon('heroicon-o-ticket'),
            'opened' => Tab::make('Opened')
                ->icon('heroicon-o-ticket')
                ->modifyQueryUsing(function ($query) {
                    $query->where('is_resolved', false);
                }),
            'resolved' => Tab::make('Resolved')
                ->icon('heroicon-o-ticket')
                ->modifyQueryUsing(function ($query) {
                    $query->where('is_resolved', true);
                }),
        ];
    }
}
