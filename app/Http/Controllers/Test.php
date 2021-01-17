<?php

namespace App\Http\Controllers;

use App\Models\API\Payment\QIWI;
use App\Models\PaymentData;
use Illuminate\Http\Request;

class Test extends Controller {
    public function index() {
        $paymentData = PaymentData::first();
        $qiwi = new QIWI($paymentData->qiwi_token, $paymentData->qiwi_webhook_id, $paymentData->qiwi_public_key);
        return json_decode($qiwi->getKey())->key;
    }
}
