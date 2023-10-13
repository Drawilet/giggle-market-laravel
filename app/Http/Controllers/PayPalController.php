<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use PayPal\Api\PayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Currency;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payout;
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
            return redirect()->back()->with("error", "There was an error creating the payment.");
        }
    }


    public function executePayment(Request $request)
    {
        $apiContext = $this->getApiContext();

        $payerId = $request->input('PayerID');
        if (empty($payerId) || !is_string($payerId)) {
            return redirect()->route("purchases")->with("error", "Invalid or missing PayerID");
        }

        $paymentId = $request->input("paymentId");
        if (empty($paymentId) || !is_string($paymentId)) {
            return redirect()->route("purchases")->with("error", " Invalid or missing PaymentID");
        }

        $payment = Payment::get($paymentId, $apiContext);

        $sale = Sale::where("payment_id", $paymentId)->first();

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        $result = $payment->execute($execution, $apiContext);

        if ($result->getState() == "approved") {
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
    }

    public function cancelPayment()
    {
        return redirect()->route('purchases')->with('info', 'Payment was canceled.');
    }

    public function payAgain($paymentId)
    {
        $apiContext = $this->getApiContext();
        $payment = Payment::get($paymentId, $apiContext);

        return redirect($payment->getApprovalLink());
    }


    public function createPayout($email, $amount)
    {
        $apiContext = $this->getApiContext();

        $payout = new Payout();
        
        $currency = new Currency();
        $currency->setCurrency("USD");
        $currency->setValue($amount);

        $item = new PayoutItem();
        $item->setRecipientType("EMAIL");
        $item->setReceiver($email);
        $item->setAmount($currency);
        $item->setNote("Simple note");

        $payout->setItems([$item]);

        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId("1");
        $senderBatchHeader->setEmailSubject("Payment subject");

        $payout->setSenderBatchHeader($senderBatchHeader);

        $payout->create(null, $apiContext);



    }
}

/** public function createPayout($email, $amount)
    {
        $apiContext = $this->getApiContext();

        $payout = new Payout();

        $currency = new Currency();
        $currency->setCurrency("USD");
        $currency->setValue($amount);

        $item = new PayoutItem();
        $item->setRecipientType("EMAIL");
        $item->setReceiver($email);
        $item->setAmount($currency);
        $item->setNote("Simple note");

        $payout->setItems([$item]);

        $senderBatchHeader = new PayoutSenderBatchHeader();
        $senderBatchHeader->setSenderBatchId("1");
        $senderBatchHeader->setEmailSubject("Payment subject");

        $payout->setSenderBatchHeader($senderBatchHeader);

        $payout->create(null, $apiContext);



    } */
