<?php

$api_key = null;

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->coinbase_api_key)) {
            $api_key = $credentials->coinbase_api_key;
        }

    }

}


if (empty($api_key)) {
    echo "Invalid Coinbase Credentials";
    exit;
}


if (isset($payment_data['purchase'])) {

    $currency = Registry::load('settings')->default_currency;

    if (!in_array(Registry::load('settings')->default_currency, array('USD'))) {

        $currency = 'USD';

        include_once "fns/currency_tools/load.php";
        $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency);

        if (empty($payment_data['purchase'])) {
            echo "Currency Conversion Failed";
            exit;
        }
    }




    $session_data = [
        'name' => $payment_data['package_name'],
        'description' => $payment_data['description'],
        'pricing_type' => 'fixed_price',
        'local_price' => array('amount' => $payment_data['purchase'], 'currency' => 'USD'),
        'metadata' => [
            'user_id' => Registry::load('current_user')->id,
            'amount' => $payment_data['purchase']
        ],
        "redirect_url" => $payment_data['validation_url'],
        'cancel_url' => $payment_data['validation_url']
    ];

    try {


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($session_data));

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'X-Cc-Api-Key: '.$api_key;
        $headers[] = 'X-Cc-Version: 2018-03-22';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $coinbase_result = curl_exec($ch);

        if (curl_errno($ch)) {
            $data = array(
                'status' => 400,
                'error' => curl_error($ch)
            );
        }
        curl_close($ch);

        $coinbase_result = json_decode($coinbase_result, true);

        if (!empty($coinbase_result) && !empty($coinbase_result['data']) && !empty($coinbase_result['data']['hosted_url']) && !empty($coinbase_result['data']['id']) && !empty($coinbase_result['data']['code'])) {


            $payment_session_data = array();
            $payment_session_data["payment_session_data"] = $coinbase_result['data']['code'];

            $payment_session_data = json_encode($payment_session_data);
            DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);

            header('Location: ' . $coinbase_result['data']['hosted_url']);
            exit();

        } else {
            redirect($payment_data['validation_url']);
        }
    }
    catch (Exception $e) {
        redirect($payment_data['validation_url']);
    }
} else if (isset($payment_data['validate_purchase'])) {


    $transaction_info = array_merge($_GET, $_POST);

    $result = array();
    $result['success'] = false;
    $result['transaction_info'] = $transaction_info;
    $result['error'] = 'something_went_wrong';
    $payment_session_data = null;


    if (isset($payment_data['payment_session_data'])) {
        $payment_session_data = $payment_data['payment_session_data'];
        $transaction_info['payment_session_data'] = $payment_session_data;
    }

    if (!empty($payment_session_data)) {

        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.commerce.coinbase.com/charges/'.$payment_session_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'X-Cc-Api-Key: '.$api_key;
            $headers[] = 'X-Cc-Version: 2018-03-22';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $coinbase_result = curl_exec($ch);
            curl_close($ch);

            $coinbase_result = json_decode($coinbase_result, true);
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }


        if (!empty($coinbase_result) && !empty($coinbase_result['data'])) {
            if (!empty($coinbase_result['data']['payments']) && !empty($coinbase_result['data']['payments'][0]['status']) && $coinbase_result['data']['payments'][0]['status'] == 'CONFIRMED') {
                $result = array();
                $result['success'] = true;
                $result['transaction_info'] = $transaction_info;

            } else if (!empty($coinbase_result['data']['timeline'])) {
                $result['error'] = ['coinbase_timeline' => $coinbase_result['data']['timeline']];
                $result['error'] = json_encode($result['error']);
            }
        }
    }
}