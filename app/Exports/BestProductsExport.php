<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BestProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return ['Produk', 'Terjual', 'Revenue'];
    }

    public function map($p): array
    {
        return [
            $p->name,
            $p->total_qty . ' unit',
            $p->total_revenue ?? 0,
        ];
    }
}
