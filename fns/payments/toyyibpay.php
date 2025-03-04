<?php

$toyyibpay_secret_key = null;
$toyyibpay_url = 'https://toyyibpay.com/';

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->toyyibpay_secret_key)) {
            $toyyibpay_secret_key = $credentials->toyyibpay_secret_key;
        }

        if (isset($credentials->toyyibpay_sandbox) && $credentials->toyyibpay_sandbox === 'yes') {
            $toyyibpay_url = 'https://dev.toyyibpay.com/';
        }

    }

}

if (empty($toyyibpay_secret_key)) {
    echo "Invalid toyyibpay Credentials";
    exit;
}


if (isset($payment_data['purchase'])) {

    $currency = Registry::load('settings')->default_currency;

    if (!in_array(Registry::load('settings')->default_currency, array('MYR'))) {

        $currency = 'MYR';

        include_once "fns/currency_tools/load.php";
        $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency, 'MYR');

        if (empty($payment_data['purchase'])) {
            echo "Currency Conversion Failed";
            exit;
        }
    }


    try {

        $payment_data['purchase'] = (int)$payment_data['purchase'];
        $payment_data['purchase'] = $payment_data['purchase']*100;

        $toyyibpay_url_category = $toyyibpay_url.'index.php/api/createCategory';
        $toyyibpay_data = array(
            'userSecretKey' => $toyyibpay_secret_key,
            'catname' => 'order_id_'.$payment_data['transactionId'],
            'catdescription' => $payment_data['description']
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $toyyibpay_url_category);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $toyyibpay_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $category_info = json_decode($response, 'true');
        $CategoryCode = null;


        if (!empty($category_info) && isset($category_info[0]) && isset($category_info[0]['CategoryCode'])) {
            $CategoryCode = $category_info[0]['CategoryCode'];
        } else if (!empty($category_info) && isset($category_info['CategoryCode'])) {
            $CategoryCode = $category_info['CategoryCode'];
        }

        if (empty($CategoryCode)) {
            redirect($payment_data['validation_url']);
        }

        $toyyibpay_url_bill = $toyyibpay_url.'index.php/api/createBill';
        $toyyibpay_data = array(
            'userSecretKey' => $toyyibpay_secret_key,
            'categoryCode' => $CategoryCode,
            'billName' => $payment_data['package_name'],
            'billDescription' => $payment_data['description'],
            'billAmount' => $payment_data['purchase'],
            'billPriceSetting' => 1,
            'billPayorInfo' => 0,
            'billReturnUrl' => $payment_data['validation_url'],
            'billCallbackUrl' => $payment_data['validation_url'],
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $toyyibpay_url_bill);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $toyyibpay_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $session = json_decode($response);

        if (!empty($session) && isset($session[0]) && isset($session[0]->BillCode)) {

            $payment_session_data = array();
            $payment_session_data["payment_session_id"] = $session[0]->BillCode;

            $payment_session_data = json_encode($payment_session_data);
            DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);

            $payment_url = $toyyibpay_url.$session[0]->BillCode;
            header('Location: ' . $payment_url);
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
    $session_id = null;


    if (isset($payment_data["payment_session_id"])) {
        $session_id = $payment_data["payment_session_id"];
        $transaction_info['payment_session_id'] = $session_id;
    }

    if (!empty($session_id)) {

        try {

            $toyyibpay_url_status = $toyyibpay_url.'index.php/api/getBillTransactions';
            $toyyibpay_data = array(
                'userSecretKey' => $toyyibpay_secret_key,
                'billCode' => $session_id,
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $toyyibpay_url_status);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $toyyibpay_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $payment_intent = json_decode($response, true);

            if (!empty($payment_intent) && isset($payment_intent[0]) && isset($payment_intent[0]['billpaymentStatus'])) {
                $payment_intent['billpaymentStatus'] = $payment_intent[0]['billpaymentStatus'];
            }

            if (!empty($payment_intent) && isset($payment_intent['billpaymentStatus'])) {

                if ((int)$payment_intent['billpaymentStatus'] === 1) {
                    $result = array();
                    $result['success'] = true;
                    $result['transaction_info'] = $transaction_info;
                } else {
                    $result['error'] = 'Failed Payment';
                }
            } else {
                $result['error'] = 'Failed Payment';
            }

        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }
    }
}