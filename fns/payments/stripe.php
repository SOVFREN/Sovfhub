<?php
include_once 'fns/payments/stripe/autoload.php';

$secret_key = null;

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->strip_secret_key)) {
            $secret_key = $credentials->strip_secret_key;
        }

    }

}


if (empty($secret_key)) {
    echo "Invalid Stripe Credentials";
    exit;
}


\Stripe\Stripe::setApiKey($secret_key);

if (isset($payment_data['purchase'])) {

    try {
        $payment_data['purchase'] = (int)$payment_data['purchase'];
        $payment_data['purchase'] = $payment_data['purchase']*100;


        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => strtolower(Registry::load('settings')->default_currency),
                        'unit_amount' => $payment_data['purchase'],
                        'product_data' => [
                            'name' => $payment_data['package_name'],
                            'description' => $payment_data['description'],
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $payment_data['validation_url'],
            'cancel_url' => $payment_data['validation_url'],
        ]);

        $payment_session_data = array();
        $payment_session_data["payment_session_id"] = $session->id;

        $payment_session_data = json_encode($payment_session_data);
        DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);


        header('Location: ' . $session->url);
        exit();
    } catch (\Stripe\Exception\ApiErrorException $e) {
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
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            $payment_intent_id = $session->payment_intent;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            if ($payment_intent->status === 'succeeded') {
                $result = array();
                $result['success'] = true;
                $result['transaction_info'] = $transaction_info;
            } else {
                $result['error'] = $payment_intent->last_payment_error['message'];
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }
    }
}