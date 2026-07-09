<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
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
        return ['Kode', 'Kasir', 'Pelanggan', 'Subtotal', 'Diskon', 'Pajak', 'Total', 'Bayar', 'Kembalian', 'Metode', 'Tgl'];
    }

    public function map($t): array
    {
        return [
            $t->code,
            $t->user->name ?? '-',
            $t->customer_name ?? '-',
            $t->subtotal,
            $t->discount_amount,
            $t->tax_amount,
            $t->total,
            $t->paid_amount,
            $t->change_amount,
            $t->payment_method,
            $t->created_at->format('d/m/Y H:i'),
        ];
    }
}
