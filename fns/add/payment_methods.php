<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {

    include_once 'fns/filters/load.php';
    include_once 'fns/files/load.php';

    $noerror = true;
    $disabled = 0;
    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    if (!isset($data['payment_method']) || empty($data['payment_method'])) {
        $result['error_variables'][] = ['payment_method'];
        $noerror = false;
    }

    if ($noerror) {

        $payment_methods = [
            'paypal', 'stripe', 'coinbase',
            'bank_transfer', 'razorpay', 'nowpayments',
            'toyyibpay', 'flutterwave', 'iyzico', 'xendit'
        ];

        if (!in_array($data['payment_method'], $payment_methods)) {
            $data['payment_method'] = 'paypal';
        }

        if (isset($data['disabled']) && $data['disabled'] === 'yes') {
            $disabled = 1;
        }

        $remove_fields = ['payment_method', 'disabled', 'add', 'update'];
        $credentials = sanitize_array($data);
        $credentials = array_diff_key($credentials, array_flip($remove_fields));

        if (isset($data['bank_account_details']) && !empty($data['bank_account_details'])) {

            include('fns/HTMLPurifier/load.php');
            $allowed_tags = 'b,i,u,strong,br';

            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', $allowed_tags);
            $config->set('Attr.AllowedClasses', array());
            $config->set('AutoFormat.RemoveEmpty', true);

            $purifier = new HTMLPurifier($config);

            $data['bank_account_details'] = $purifier->purify(trim($data['bank_account_details']));
            $credentials['bank_account_details'] = $data['bank_account_details'];
        }

        $credentials = json_encode($credentials);


        DB::connect()->insert("payment_gateways", [
            "identifier" => $data['payment_method'],
            "credentials" => $credentials,
            "disabled" => $disabled,
            "updated_on" => Registry::load('current_user')->time_stamp,
        ]);

        if (!DB::connect()->error) {

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'payment_methods';
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
            $result['error_key'] = 'something_went_wrong';
        }

    }
}

?>