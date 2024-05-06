<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function __invoke(TicketRequest $request): TicketResource
    {
        $ticket = Ticket::query()->create([
            'device_id' => $request->user()->id,
        ] + $request->validated());

        return new TicketResource($ticket);
    }
}
