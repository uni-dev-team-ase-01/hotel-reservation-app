<?php

namespace App\Observers;

use App\Enum\PaymentStatus;
use App\Mail\BillMarkAsPaid;
use App\Models\Bill;
use App\Services\BillService as BillServiceService;
use Illuminate\Support\Facades\Mail;

class BillObserver
{
    /**
     * Handle the Reservation "saved" event.
     */
    public function saved(Bill $bill): void
    {
        logger()->info('Bill saved', [
            'bill_id' => $bill->id,
            'status' => $bill->status,
            'reservation_id' => $bill->reservation_id,
        ]);
        if (!$bill->reservation->user) {
            return;
        }

        
        if (
            $bill->wasChanged('taxes') ||
            $bill->wasChanged('room_charges') ||
            $bill->wasChanged('extra_charges') ||
            $bill->wasChanged('discount')
        ) {
            $this->reCalculateBill($bill);
        }

        if ($bill->payment_status === PaymentStatus::PAID->value) {
            Mail::to($bill->reservation->user)->send(new BillMarkAsPaid($bill));
        }
    }

    private function reCalculateBill($bill)
    {
        $billServiceService = new BillServiceService();
        $billServiceService->calculateTotalAmountOfBill($bill->reservation_id);
    }
}
