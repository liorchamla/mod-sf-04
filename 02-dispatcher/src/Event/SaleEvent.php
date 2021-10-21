<?php

namespace App\Event;

class SaleEvent
{
    public string $email;
    public string $clientName;
    public int $amount;

    public function __construct(string $email, string $clientName, int $amount)
    {
        $this->amount = $amount;
        $this->email = $email;
        $this->clientName = $clientName;
    }
}
