<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\DTOs\Payments\StorePaymentDTO;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

final class StorePaymentAction
{
    public function execute(StorePaymentDTO $dto): Payment
    {
        return DB::transaction(function () use ($dto) {
            $payment = Payment::create([
                'invoice_id'       => $dto->invoice_id,
                'amount'           => $dto->amount,
                'method'           => $dto->method,
                'reference_number' => $dto->reference_number,
                'paid_at'          => $dto->paid_at ?? now(),
            ]);

            $invoice = $payment->invoice;
            $this->updateInvoiceStatus($invoice);

            return $payment;
        });
    }

    private function updateInvoiceStatus(Invoice $invoice): void
    {
        $totalPaid = $invoice->paid_amount;
        $totalAmount = $invoice->total_amount;

        if ($totalPaid >= $totalAmount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partially_paid']);
        } else {
            $invoice->update(['status' => 'unpaid']);
        }
    }
}
