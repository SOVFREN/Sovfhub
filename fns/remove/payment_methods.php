<?php

$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$payment_gateway_ids = array();

if (role(['permissions' => ['super_privileges' => 'manage_payment_gateways']])) {
    
    if (isset($data['payment_gateway_id'])) {
        if (!is_array($data['payment_gateway_id'])) {
            $data["payment_gateway_id"] = filter_var($data["payment_gateway_id"], FILTER_SANITIZE_NUMBER_INT);
            $payment_gateway_ids[] = $data["payment_gateway_id"];
        } else {
            $payment_gateway_ids = array_filter($data["payment_gateway_id"], 'ctype_digit');
        }
    }

    if (!empty($payment_gateway_ids)) {

        DB::connect()->delete("payment_gateways", ["payment_gateway_id" => $payment_gateway_ids]);

        if (!DB::connect()->error) {

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'payment_methods';
        } else {
            $result['error_message'] = Registry::load('strings')->went_wrong;
        }
    }
}
?>