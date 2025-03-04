<?php

if (role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {

    $todo = 'add';

    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();

    if (isset($load["payment_gateway_id"])) {

        $todo = 'update';


        $columns = $join = $where = null;
        $columns = [
            'payment_gateways.payment_gateway_id ', 'payment_gateways.identifier', 'payment_gateways.credentials',
            'payment_gateways.disabled'
        ];

        $where["payment_gateways.payment_gateway_id"] = $load["payment_gateway_id"];
        $where["LIMIT"] = 1;

        $method = DB::connect()->select('payment_gateways', $columns, $where);

        if (!isset($method[0])) {
            return false;
        } else {
            $method = $method[0];
        }

        $form['fields']->payment_gateway_id = [
            "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["payment_gateway_id"]
        ];

        $form['loaded']->title = Registry::load('strings')->edit_custom_field;
        $form['loaded']->button = Registry::load('strings')->update;
    } else {
        $form['loaded']->title = Registry::load('strings')->add_payment_method;
        $form['loaded']->button = Registry::load('strings')->add;
    }

    $form['fields']->$todo = [
        "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "payment_methods"
    ];

    $form['fields']->payment_method = [
        "title" => Registry::load('strings')->payment_method, "tag" => 'select', "class" => 'field toggle_form_fields'
    ];
    $form['fields']->payment_method ["options"] = [
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
        'coinbase' => 'Coinbase',
        'razorpay' => 'Razorpay',
        'iyzico' => 'iyzico',
        'xendit' => 'Xendit',
        'bank_transfer' => 'Bank Transfer',
        'nowpayments' => 'NOWPayments',
        'toyyibpay' => 'toyyibPay',
        'flutterwave' => 'Flutterwave'
    ];

    $form['fields']->payment_method["attributes"] = [
        "hide_field" => "payment_method_fields",
    ];


    $form['fields']->flutterwave_secret_key = [
        "title" => 'Flutterwave Secret Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields flutterwave_fields',
    ];

    
    $form['fields']->xendit_api_key = [
        "title" => 'Xendit API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields xendit_fields',
    ];
    

    $form['fields']->toyyibpay_secret_key = [
        "title" => 'toyyibPay Secret Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields toyyibpay_fields',
    ];


    $form['fields']->toyyibpay_sandbox = [
        "title" => 'Sandbox Account', "tag" => 'select',
        "class" => 'field payment_method_fields toyyibpay_fields',
    ];

    $form['fields']->toyyibpay_sandbox ["options"] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no
    ];


    $form['fields']->iyzico_api_key = [
        "title" => 'iyzico API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields iyzico_fields',
    ];

    $form['fields']->iyzico_secret_key = [
        "title" => 'iyzico Secret Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields iyzico_fields',
    ];


    $form['fields']->iyzico_sandbox = [
        "title" => 'Sandbox Account', "tag" => 'select',
        "class" => 'field payment_method_fields iyzico_fields',
    ];

    $form['fields']->iyzico_sandbox ["options"] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no
    ];

    $form['fields']->nowpayments_api_key = [
        "title" => 'NOWPayments API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields nowpayments_fields',
    ];


    $form['fields']->nowpayments_sandbox = [
        "title" => 'Sandbox Account', "tag" => 'select',
        "class" => 'field payment_method_fields nowpayments_fields',
    ];

    $form['fields']->nowpayments_sandbox ["options"] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no
    ];


    $form['fields']->paypal_client_id = [
        "title" => 'Client ID', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields paypal_fields',
    ];

    $form['fields']->paypal_client_secret = [
        "title" => 'Client Secret', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields paypal_fields',
    ];

    $form['fields']->paypal_test_mode = [
        "title" => 'Test Mode', "tag" => 'select',
        "class" => 'field payment_method_fields paypal_fields',
    ];

    $form['fields']->paypal_test_mode ["options"] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no
    ];


    $form['fields']->strip_secret_key = [
        "title" => 'Secret API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields stripe_fields',
    ];


    $form['fields']->coinbase_api_key = [
        "title" => 'Coinbase API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields coinbase_fields',
    ];


    $form['fields']->razorpay_api_key = [
        "title" => 'Razorpay API Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields razorpay_fields',
    ];

    $form['fields']->razorpay_secret_key = [
        "title" => 'Razorpay Secret Key', "tag" => 'input', "type" => "text",
        "class" => 'field payment_method_fields razorpay_fields',
    ];


    $form['fields']->bank_account_details = [
        "title" => 'Bank Account Details', "tag" => 'textarea',
        "class" => 'field payment_method_fields bank_transfer_fields',
    ];


    $form['fields']->disabled = [
        "title" => Registry::load('strings')->disabled, "tag" => 'select', "class" => 'field'
    ];
    $form['fields']->disabled['options'] = [
        "yes" => Registry::load('strings')->yes,
        "no" => Registry::load('strings')->no,
    ];




    if (isset($load["payment_gateway_id"])) {
        $disabled = 'no';

        if ((int)$method['disabled'] === 1) {
            $disabled = 'yes';
        }

        $form['fields']->payment_method["value"] = $method['identifier'];
        $form['fields']->disabled["value"] = $disabled;

        if (!empty($method['credentials'])) {
            $credentials = json_decode($method['credentials']);

            if (!empty($credentials)) {
                foreach ($credentials as $field_name => $credential) {
                    if (isset($form['fields']->$field_name)) {
                        $form['fields']->$field_name["value"] = $credential;
                    }
                }
            }
        }

    }
}
?>