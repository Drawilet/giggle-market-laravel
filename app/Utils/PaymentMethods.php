<?php

namespace App\Utils;

use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\PayPalController;
use Illuminate\Support\Facades\Auth;

class PaymentMethods
{
    public static function get()
    {
        return [
            "paypal" => [
                "label"=> "Paypal (".Auth::user()->paypal_email.")",
                "controller" =>  PayPalController::class,
                "button" => [
                    "bg" => "rgb(253 224 71)",
                    "logo" => "paypal.png",
                ],
                "allowPayments" => true,
                "allowWithdraw" => true
            ],
            "mercadopago" => [
                "label"=> "MercadoPago",
                "controller" => MercadoPagoController::class,
                "button" => [
                    "bg" => " rgb(56 189 248)",
                    "logo" => "mercadopago.webp",
                ],
                "allowPayments" => true,
                "allowWithdraw" => false
            ]
        ];
    }
}
