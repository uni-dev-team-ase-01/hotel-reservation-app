<?php

namespace App\Services;

class BillService
{
    public function calculateTotal(array $items): float
    {
        $total = 0.0;

        foreach ($items as $item => $price) {
            if (is_numeric($price)) {
                $total += (float)$price;
            }
        }

        return round($total, 2);
    }
}
