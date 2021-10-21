<?php

use App\Controller\ShopController;
use App\Dispatcher\EventDispatcher;
use App\Event\SaleEvent;

require_once __DIR__ . '/vendor/autoload.php';

$dispatcher = new EventDispatcher;

class StockEmail
{
    public static function getSubscribedEvents()
    {
        return [
            'sale' => 'sendEmailToStock',
            'pre_sale' => 'sendSmsToClient'
        ];
    }

    public function sendSmsToClient()
    {
    }

    public function sendEmailToStock(SaleEvent $data)
    {
        var_dump("20 lignes qui envoient un mail au stock");
        var_dump("Montant : ", $data->amount);
    }
}

$dispatcher->addSubscriber(StockEmail::class);

$dispatcher->addListener('sale', [new StockEmail, 'sendEmailToStock']);
$dispatcher->addListener('pre_sale', [new StockEmail, 'sendSmsToClient']);


$dispatcher->addListener('sale', function () {
    var_dump("20 lignes qui envoient un mail au client");
});

$dispatcher->addListener('sale', function ($data) {
    var_dump("20 lignes qui envoient un SMS au client");
});

$dispatcher->addListener('sale', function ($data) {
    var_dump("2 lignes qui loggent dans un fichier");
});

$dispatcher->addListener('pre_sale', function () {
    var_dump("AVANT LA VENTE");
});

$controller = new ShopController($dispatcher);

$controller->buy("Lior", "lior@mail.com", 2000);
