<?php

namespace App\Observers;

use App\Models\Invoice;

class InvoiceObserver
{
    public function creating(Invoice $invoice): void
    {
        // Genera il numero fattura automaticamente
        $lastInvoice = Invoice::withTrashed()
            ->where('team_id', $invoice->team_id)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastInvoice ? (intval(substr($lastInvoice->number, 4)) + 1) : 1;
        
        $invoice->number = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}