<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ShippingSheetExport implements FromCollection
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
}
