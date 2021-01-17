<?php


namespace App\Http\Controllers;


use App\Models\API\Payment\PayPal\PayPal;
use App\Models\API\Payment\QIWI;
use App\Models\API\Payment\WebMoney;
use App\Models\API\Payment\YandexMoney;
use App\Models\BotUsers;
use App\Models\PaymentData;

class Payment extends Controller {
    private $paymentData;

    public function __construct() {
        $this->paymentData = PaymentData::first();
    }

    public function qiwiHandler() {
        $qiwi = new QIWI(
            $this->paymentData->qiwi_token,
            $this->paymentData->qiwi_webhook_id,
            $this->paymentData->qiwi_public_key);
        $qiwi->handler();
    }

    public function yandexHandler() {
        $ym = new YandexMoney(
            $this->paymentData->yandex_money_secret_key,
            $this->paymentData->yandex_money_wallet);
        $ym->handler();
    }

    public function webmoneyHandler() {
        $wm = new WebMoney(
            $this->paymentData->webmoney_secret_key,
            $this->paymentData->webmoney_wallet
        );
        $wm->handler();
    }

    public function paypalHandler() {
        $paypal = new PayPal("facilitator", "RUB");
        $paypal->handler();
    }
}
