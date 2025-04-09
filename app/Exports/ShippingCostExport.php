<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ShippingCostExport implements FromCollection, WithHeadings
{
    protected $data;

    // Constructor to accept the collection
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Collection method for export
    public function collection()
    {
        return $this->data;
    }

        public function headings(): array
        {
            return [
                'city',
                'cost',
            ];
        }
}
