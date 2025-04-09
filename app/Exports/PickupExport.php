<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PickupExport implements FromCollection, WithHeadings
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return collect($this->sales);
    }

    public function headings(): array
    {
        return [
            'AWB', 'Name', 'Addr1', 'Addr2', 'Phone', 'Mobile', 
            'City', 'Zone', 'Contents', 'Weight', 'Pieces', 
            'Shipping Cost', 'Special Instructions', 'Contact Person', 
            'COD', 'AWBxAWB'
        ];
    }
}
