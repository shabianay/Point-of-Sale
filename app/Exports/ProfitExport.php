<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProfitExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return ['Kode', 'Tgl', 'Subtotal', 'Diskon', 'Pajak', 'Service', 'Total'];
    }

    public function map($t): array
    {
        return [
            $t->code,
            $t->created_at->format('d/m/Y'),
            $t->subtotal,
            $t->discount_amount,
            $t->tax_amount,
            $t->service_charge_amount ?? 0,
            $t->total,
        ];
    }
}
