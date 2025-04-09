<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PickupAccountingExport implements FromCollection, WithHeadings
{
    protected $accountingData;

    public function __construct($accountingData)
    {
        $this->accountingData = $accountingData;
    }

    public function collection()
    {
        return collect($this->accountingData);
    }

    public function headings(): array
    {
        return [
            'AWB', 'Name', 'Addr1', 'Mobile', 'City', 'Zone', 
            'Subtotal', 'Shipping', 'Total', 'Payment Method', 
            'Payment Reference', 'Special Instructions', 'Shipping Note'
        ];
    }
}
