<?php
include_once 'fns/payments/razorpay/autoload.php';

use Razorpay\Api\Api;

$razorpay_api_key = null;

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->razorpay_api_key)) {
            $razorpay_api_key = $credentials->razorpay_api_key;
            $razorpay_secret_key = $credentials->razorpay_secret_key;
        }

    }

}


if (empty($razorpay_api_key)) {
    echo "Invalid Razorpay Credentials";
    exit;
}

if (isset($payment_data['purchase'])) {

    $currency = Registry::load('settings')->default_currency;

    include_once "fns/data_arrays/razorpay_currencies.php";

    if (!in_array(Registry::load('settings')->default_currency, $razorpay_currencies)) {

        $currency = 'USD';

        include_once "fns/currency_tools/load.php";
        $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency);

        if (empty($payment_data['purchase'])) {
            echo "Currency Conversion Failed";
            exit;
        }
    }

    try {

        $razorpay_api = new Api($razorpay_api_key, $razorpay_secret_key);
        $payment_data['purchase'] = (int)$payment_data['purchase'];
        $payment_data['purchase'] = $payment_data['purchase']*100;

        $session = [
            'receipt' => $payment_data['transactionId'],
            'amount' => $payment_data['purchase'],
            'currency' => $currency,
            'notes' => [
                'name' => $payment_data['package_name'],
                'description' => $payment_data['description'],
            ]
        ];

        $session = $razorpay_api->order->create($session);

        $payment_session_data = array();
        $payment_session_data["payment_session_id"] = $session->id;

        $payment_session_data = json_encode($payment_session_data);
        DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);

        ?>


        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Checkout Page</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    background-color: #f0f0f0;
                }

                .container {
                    text-align: center;
                }

                .pay-button {
                    padding: 15px 30px;
                    font-size: 16px;
                    cursor: pointer;
                    background-color: #3498db;
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    transition: background-color 0.3s;
                }

                .pay-button:hover {
                    background-color: #2980b9;
                }
            </style>
        </head>
        <body>

            <div class="container">
                <button class="pay-button" id="rzp-button1">Click to Pay</button>
            </div>

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
                var options = {
                    "key": <?php echo '"'.$razorpay_api_key.'"' ?>,
                    "amount": <?php echo '"'.$payment_data['purchase'].'"' ?>,
                    "currency": <?php echo '"'.$currency.'"' ?>,
                    "name": <?php echo '"'.Registry::load('settings')->site_name.'"' ?>,
                    "description": <?php echo '"'.$payment_data['package_name'].'"' ?>,
                    "order_id": <?php echo '"'.$session->id.'"' ?>,
                    "callback_url": <?php echo '"'.$payment_data['validation_url'].'"' ?>,
                    "redirect": true,
                    "handler": function (response) {}
                };
                var rzp1 = new Razorpay(options);
                document.getElementById('rzp-button1').onclick = function(e) {
                    rzp1.open();
                    e.preventDefault();
                }
            </script>

        </body>
    </html>

    <?php
    exit();
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

        $razorpay_api = new Api($razorpay_api_key, $razorpay_secret_key);

        $payment_intent = $razorpay_api->order->fetch($session_id);

        if ($payment_intent->status === 'paid') {
            $result = array();
            $result['success'] = true;
            $result['transaction_info'] = $transaction_info;
        } else {
            $result['error'] = 'Payment Failed';
        }

    }  catch (Exception $e) {
        $result['error'] = $e->getMessage();
    }
}
}