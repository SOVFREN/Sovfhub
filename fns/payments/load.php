<?php
function payment_module($payment_data) {
    $result = array();

    if (isset($payment_data['gateway'])) {

        $find_gateway = $payment_data['gateway'];

        if (!empty($find_gateway)) {
            $find_gateway = preg_replace("/[^a-zA-Z0-9_]+/", "", $find_gateway);
            $find_gateway = str_replace('libraries', '', $find_gateway);
        }

        if (!empty($find_gateway)) {
            $function_file = 'fns/payments/'.$find_gateway.'.php';
            if (file_exists($function_file)) {
                include($function_file);
            }
        }
    }

    return $result;
}