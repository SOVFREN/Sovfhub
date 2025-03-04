<?php
$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$order_ids = array();

if (role(['permissions' => ['memberships' => 'edit_site_transactions']])) {

    if (isset($data['order_id'])) {
        if (!is_array($data['order_id'])) {
            $data["order_id"] = filter_var($data["order_id"], FILTER_SANITIZE_NUMBER_INT);
            $order_ids[] = $data["order_id"];
        } else {
            $order_ids = array_filter($data["order_id"], 'ctype_digit');
        }
    }

    if (isset($data['order_id']) && !empty($data['order_id'])) {

        DB::connect()->delete("membership_orders", ["order_id" => $order_ids]);

        if (!DB::connect()->error) {
            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = ['site_transactions', 'transactions'];
        } else {
            $result['errormsg'] = Registry::load('strings')->went_wrong;
        }
    }
}
?>