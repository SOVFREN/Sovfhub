<?php

if (isset($payment_data['purchase']) || isset($payment_data['validate_purchase'])) {

    if (isset($payment_data['validate_purchase'])) {
        $payment_data['transactionId'] = $payment_data['validate_purchase'];
    }

    $bank_transfer_url = Registry::load('config')->site_url.'bank_transfer/'.$payment_data['transactionId'].'/';
    redirect($bank_transfer_url);
}