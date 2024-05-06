<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TicketStatsChart extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Ticket', Ticket::count())
                ->description('Total ticket count')
                ->color('primary')
                ->icon('heroicon-o-ticket'),

            Stat::make('Opened Ticket', Ticket::whereIsResolved(false)->count())
                ->description('Opened ticket count')
                ->color('danger')
                ->icon('heroicon-o-ticket'),

            Stat::make('Resolved Ticket', Ticket::whereIsResolved(true)->count())
                ->description('Resolved ticket count')
                ->color('success')
                ->icon('heroicon-o-ticket'),

        ];
    }
}
