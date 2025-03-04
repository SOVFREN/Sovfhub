<?php
require_once 'fns/payments/omnipay/autoload.php';

use Omnipay\Omnipay;

$paypal_client_id = null;
$paypal_client_secret = null;
$paypal_testmode = false;

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->paypal_client_id) && isset($credentials->paypal_client_secret)) {
            $paypal_client_id = $credentials->paypal_client_id;
            $paypal_client_secret = $credentials->paypal_client_secret;
        }

        if (isset($credentials->paypal_test_mode) && $credentials->paypal_test_mode === 'yes') {
            $paypal_testmode = true;
        }
    }

}


if (empty($paypal_client_id) || empty($paypal_client_secret)) {
    echo "Invalid PayPal Credentials";
    exit;
}

$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId($paypal_client_id);
$gateway->setSecret($paypal_client_secret);
$gateway->setTestMode($paypal_testmode);

if (isset($payment_data['purchase'])) {

    $currency = Registry::load('settings')->default_currency;

    include_once "fns/data_arrays/paypal_currencies.php";

    if (!in_array(Registry::load('settings')->default_currency, $paypal_currencies)) {

        $currency = 'USD';

        include_once "fns/currency_tools/load.php";
        $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency);

        if (empty($payment_data['purchase'])) {
            echo "Currency Conversion Failed";
            exit;
        }
    }

    $payment_data['purchase'] = round($payment_data['purchase']);

    $payment_params = [
        'transactionId' => $payment_data['transactionId'],
        'amount' => $payment_data['purchase'],
        'currency' => $currency,
        'description' => $payment_data['description'],
        'returnUrl' => $payment_data['validation_url'],
        'cancelUrl' => $payment_data['validation_url'],
    ];

    $payment_response = $gateway->purchase($payment_params)->send();

    if ($payment_response->isRedirect()) {
        $payment_response->redirect();
    } else if ($payment_response->isSuccessful()) {
        redirect($payment_data['validation_url']);
    } else {
        redirect($payment_data['validation_url']);
    }
} else if (isset($payment_data['validate_purchase'])) {

    $payment_id = $_POST['paymentId'] ?? $_GET['paymentId'] ?? null;
    $payer_id = $_POST['PayerID'] ?? $_GET['PayerID'] ?? null;
    $transaction_info = array_merge($_GET, $_POST);


    $result = array();
    $result['success'] = false;
    $result['transaction_info'] = $transaction_info;
    $result['error'] = 'something_went_wrong';

    if (!empty($payment_id) && !empty($payer_id)) {
        $pass_parameters = ['transactionReference' => $payment_id, 'PayerID' => $payer_id];
        $payment_response = $gateway->completePurchase($pass_parameters)->send();

        if ($payment_response->isSuccessful()) {
            $result = array();
            $result['success'] = true;
            $result['transaction_info'] = $transaction_info;
        } else {
            $result['error'] = $payment_response->getMessage();
        }
    }
}