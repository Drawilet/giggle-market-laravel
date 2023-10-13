<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;

//require_once 'vendor/autoload.php';

class MercadoPagoController extends Controller
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config("services.mercadopago.access_token"));
    }

    public function createPayment(Sale $sale)
    {
        $client  = new PreferenceClient();
        $items = [];

        foreach ($sale->descriptions as $item) {
            $items[] = [
                "id" => $item->id,
                "title" => $item->description,
                //   "description" => $item->tenant_name,
                "quantity" => $item->quantity,
                "currency_id" => "USD",
                "unit_price" => floatval($item->price)
            ];
        }

        $origin = request()->header("origin");

        try {
            $preference =  $client->create([
                "items" => $items,
                "back_urls" => [
                    "success" => "$origin/payment/mercadopago/execute",
                    "pending" => "$origin/payment/mercadopago/pending",
                    "failure" => "$origin/payment/mercadopago/cancel",
                ],
                "auto_return" => "all",
                "external_reference" => $sale->id,
            ]);
            $sale->payment_id = $preference->id;
            $sale->save();

            redirect($preference->init_point);
        } catch (MPApiException $e) {
            redirect()->route("purchases")->with("error", $e->getApiResponse()->getContent()["message"]);
        } catch (\Exception $e) {
            redirect()->route("purchases")->with("error", $e->getMessage());
        }
    }


    public function executePayment(Request $request)
    {
        $paymentId = $request->input('payment_id');
        if (empty($paymentId) || !is_string($paymentId)) {
            return redirect()->route("purchases")->with("error", "Invalid or missing Payment Id");
        }

        $paymentClient = new PaymentClient();

        try {

            $payment = $paymentClient->get($paymentId);

            $sale = Sale::where("id", $payment->external_reference)->first();
            if ($sale->payment_status == "approved")
                return redirect()->route("purchases")->with("error", "Payment have already processed");

            if ($payment->status == "approved") {
                $sale->payment_id = $paymentId;
                $sale->payment_status = "approved";
                $sale->save();

                foreach ($sale->descriptions as $description) {
                    $transaction = new TransactionController();

                    $transaction->setPayer("Giggle Market", "system");
                    $transaction->setRecepient($description->tenant, "tenant");

                    $transaction->setDescription("Sale of $description->description");
                    $transaction->setAmount($description->quantity * $description->price);

                    $transaction->execute();
                }


                return redirect()->route("purchases")->with("success", "Purchase successful! Thank you for your order.");
            } else {
                return redirect()->route("purchases")->with("error", "Payment was not successful.");
            }
        } catch (MPApiException $e) {
            redirect()->route("purchases")->with("error", $e->getApiResponse()->getContent()["message"]);
        } catch (\Exception $e) {
            redirect()->route("purchases")->with("error", $e->getMessage());
        }
    }

    public function cancelPayment()
    {
        return redirect()->route('purchases')->with('info', 'Payment was canceled.');
    }

    public function waitingPayment()
    {
        return redirect()->route('purchases')->with('info', 'Waiting for payment approval');
    }
}
