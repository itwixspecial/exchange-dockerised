<?php

namespace App\Services;

class CommissionService
{
    public function calculateCommission(float $amount): float
    {
        return $amount * 0.02; // Розрахунок комісії (2%), сюди додаватиметься логіка, якщо вона буде складнішою
    }
}
