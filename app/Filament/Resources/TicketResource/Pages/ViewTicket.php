<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    public bool $disable = false;

    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->slideOver()
        ];
    }
}
