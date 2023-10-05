<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;

class PayPalController extends Controller
{

    public function getApiContext()
    {
        return  new ApiContext(new OAuthTokenCredential(
            config("services.paypal.client_id"),
            config("services.paypal.secret")
        ));
    }

    public function createPayment(Sale $sale)
    {
        $apiContext = $this->getApiContext();

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new Amount();
        $amount->setTotal($sale->amount);
        $amount->setCurrency("USD");

        $transaction = new Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(url("/payment/paypal/execute"));
        $redirectUrls->setCancelUrl(url("/payment/paypal/cancel"));

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions([$transaction]);
        $payment->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);

            $sale->payment_id = $payment->id;
            $sale->save();

            return redirect($payment->getApprovalLink());
        } catch (\Exception $ex) {
            return redirect()->back()->with("error", "[Paypal] There was an error creating the payment.");
        }
    }


    public function executePayment(Request $request)
    {
        $apiContext = $this->getApiContext();

        $payerId = $request->input('PayerID');
        if (empty($payerId) || !is_string($payerId)) {
            return redirect()->route("payment.cancel")->with("error", "[Paypal] Invalid or missing PayerID");
        }

        $paymentId = $request->input("paymentId");
        if (empty($paymentId) || !is_string($paymentId)) {
            return redirect()->route("payment.cancel")->with("error", "[Paypal]  Invalid or missing PaymentID");
        }

        try {
            $payment = Payment::get($paymentId, $apiContext);

            $sale = Sale::where("payment_id", $paymentId)->first();

            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            $result = $payment->execute($execution, $apiContext);

            if ($result->getState() == "approved") {
                $sale->payment_status = "approved";
                $sale->save();

                return redirect()->route("payment.success");
            } else {
                return redirect()->route("payment.cancel")->with("error", "[Paypal] Payment was not successful.");
            }
        } catch (\Exception $ex) {
            return redirect()->route('payment.cancel')->with('error', '[Paypal] Error getting payment details.');
        }
    }

    public function cancelPayment()
    {
        return redirect()->route('payment.cancel')->with('info', 'Payment was canceled.');
    }

    public function payAgain($paymentId)
    {
        $apiContext = $this->getApiContext();
        $payment = Payment::get($paymentId, $apiContext);

        return redirect($payment->getApprovalLink());
    }
}
