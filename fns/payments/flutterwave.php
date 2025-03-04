<?php

$flutterwave_secret_key = null;

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->flutterwave_secret_key)) {
            $flutterwave_secret_key = $credentials->flutterwave_secret_key;
        }
    }

}

if (empty($flutterwave_secret_key)) {
    echo "Invalid Flutterwave Credentials";
    exit;
}


if (isset($payment_data['purchase'])) {

    $currency = Registry::load('settings')->default_currency;

    include_once "fns/data_arrays/flutterwave_currencies.php";

    if (!in_array(Registry::load('settings')->default_currency, $flutterwave_currencies)) {

        $currency = 'USD';

        include_once "fns/currency_tools/load.php";
        $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency);

        if (empty($payment_data['purchase'])) {
            echo "Currency Conversion Failed";
            exit;
        }
    }


    try {

        $flutterwave_url = 'https://api.flutterwave.com/v3/payments';
        $flutterwave_data = array(
            'tx_ref' => $payment_data['transactionId'],
            'amount' => $payment_data['purchase'],
            'currency' => $currency,
            'redirect_url' => $payment_data['validation_url'],
            'customer' => [
                'name' => Registry::load('current_user')->name,
                'email' => Registry::load('current_user')->email_address,
            ],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $flutterwave_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($flutterwave_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $flutterwave_secret_key,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $session = json_decode($response, true);

        if (!empty($session) && isset($session['data']) && isset($session['data']['link'])) {

            $payment_session_data = array();
            $payment_session_data["payment_session_id"] = $payment_data['transactionId'];

            $payment_session_data = json_encode($payment_session_data);
            DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);


            header('Location: ' . $session['data']['link']);
            exit();
        } else {
            redirect($payment_data['validation_url']);
        }
    } catch (Exception $e) {
        redirect($payment_data['validation_url']);
    }
} else if (isset($payment_data['validate_purchase'])) {

    $transaction_info = array_merge($_GET, $_POST);

    $result = array();
    $result['success'] = false;
    $result['transaction_info'] = $transaction_info;
    $result['error'] = 'something_went_wrong';
    $session_id = $fw_transaction_id = null;


    if (isset($payment_data["payment_session_id"])) {
        $session_id = $payment_data["payment_session_id"];
        $transaction_info['payment_session_id'] = $session_id;
    }

    if (isset($_POST['transaction_id']) || isset($_GET['transaction_id'])) {
        $fw_transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : $_GET['transaction_id'];

    }

    if (!empty($session_id) && !empty($fw_transaction_id)) {

        try {

            $flutterwave_url = 'https://api.flutterwave.com/v3/transactions/'.$fw_transaction_id.'/verify';
            $transaction_info['fw_transaction_id'] = $fw_transaction_id;


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $flutterwave_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $flutterwave_secret_key,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $payment_intent = json_decode($response);

            if (!empty($payment_intent) && isset($payment_intent->tx_ref)) {
                if ((int)$payment_intent->tx_ref !== (int)$session_id) {
                    $payment_intent = null;
                }

            }

            if (!empty($payment_intent) && isset($payment_intent->status)) {

                if ($payment_intent->status === 'success') {
                    $result = array();
                    $result['success'] = true;
                    $result['transaction_info'] = $transaction_info;
                } else {
                    $result['error'] = 'Payment Failed';
                }
            } else {
                $result['error'] = 'Failed Payment';
            }

        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }
    }
}