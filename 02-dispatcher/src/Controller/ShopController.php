<?php

namespace App\Controller;

use App\Dispatcher\EventDispatcher;
use App\Event\SaleEvent;

class ShopController
{
    private EventDispatcher $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function buy(string $clientName, string $email, int $amount)
    {
        $this->dispatcher->dispatch('pre_sale', new SaleEvent($email, $clientName, $amount));

        // 1. Stocker dans la base de données
        var_dump("20 lignes qui stockent dans la BDD");

        // 2. Délencher l'événement "sale"
        $this->dispatcher->dispatch("sale", new SaleEvent($email, $clientName, $amount));
    }
}
