<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FailedProductsExport implements FromCollection, WithHeadings
{
    protected $failedProducts;

    public function __construct(array $failedProducts)
    {
        $this->failedProducts = $failedProducts;
    }

    /**
     * Return the collection of failed products.
     */
    public function collection()
    {
        return collect($this->failedProducts);
    }

    /**
     * Headings for the Excel sheet.
     */
    public function headings(): array
    {
        return [
            'Title',
            'SKU',
            'Available'
            
        ];
    }
}
