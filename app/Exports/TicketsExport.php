<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldQueue;

class TicketsExport implements FromCollection, WithHeadings, ShouldQueue
{
    protected $tickets;

    /**
     * Constructor to pass tickets data.
     */
    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
    }

    /**
     * Return the data for the export.
     */
    public function collection()
    {
        // Customize the data to be exported
        return $this->tickets->map(function ($ticket) {
            return [
                'Created At' => optional($ticket->created_at)->format('d M Y, H:i') ?? 'N/A',
                'Order Number' => $ticket->order_number ?? 'N/A',
                'Ticket Type' => optional($ticket->ticket)->type ?? 'N/A',
                'User' => optional($ticket->user)->name ?? 'N/A',
                'Status' => ucfirst($ticket->status ?? 'N/A'),
                'Reason' => $ticket->content ?? 'N/A',
            ];
        });
    }

    /**
     * Define the headings for the exported file.
     */
    public function headings(): array
    {
        return [
            'Created At',
            'Order Number',
            'Ticket Type',
            'User',
            'Status',
            'Reason',
        ];
    }
}
