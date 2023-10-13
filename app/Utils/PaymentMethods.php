<?php

namespace App\Utils;

use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\PayPalController;

class PaymentMethods
{



    public static function get()
    {
        return [
            "paypal" => [
                "controller" =>  PayPalController::class,
                "button" => [
                    "bg" => "rgb(253 224 71)",
                    "logo" => "paypal.png",
                ],
                "allowPayments" => true
            ],
            "mercadopago" => [
                "controller" => MercadoPagoController::class,
                "button" => [
                    "bg" => " rgb(56 189 248)",
                    "logo" => "mercadopago.webp",
                ],
                "allowPayments" => true
            ]
        ];
    }
}
