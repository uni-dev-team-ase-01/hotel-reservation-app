<?php

namespace App\Observers;

use App\Models\Bill;
use App\Services\BillService as BillServiceService;

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
    }

    private function reCalculateBill($bill)
    {
        $billServiceService = new BillServiceService();
        $billServiceService->calculateTotalAmountOfBill($bill->reservation_id);
    }
}
