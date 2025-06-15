<?php

namespace App\Observers;

use App\Services\BillService as BillServiceService;

class BillServiceObserver
{
    /**
     * Handle the BillService "created" event.
     */
    public function created($billService): void
    {
        $this->updateExtraChargesForBill($billService);
    }

    /**
     * Handle the BillService "updated" event.
     */
    public function updated($billService): void
    {
        $this->updateExtraChargesForBill($billService);
    }

    /**
     * Handle the BillService "deleted" event.
     */
    public function deleted($billService): void
    {
        $this->updateExtraChargesForBill($billService);
    }

    private function updateExtraChargesForBill($billService)
    {
        $billServiceService = new BillServiceService();
        $billServiceService->setExtraCharges(
            $billService->bill->reservation_id,
            $billServiceService->calculateExtraCharges($billService->bill->reservation_id)
        );
    }
}
