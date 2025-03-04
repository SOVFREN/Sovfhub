<?php

$domain_url_path = urldecode(Registry::load('config')->url_path);
$domain_url_path = preg_split('/\//', $domain_url_path);
$order_id = null;

if (isset($domain_url_path[1])) {
    $order_id = filter_var($domain_url_path[1], FILTER_SANITIZE_NUMBER_INT);
}

if (!empty($order_id)) {
    $columns = $join = $where = null;
    $gateway = array();
    $columns = [
        'membership_orders.payment_gateway_id', 'membership_orders.user_id',
        'membership_orders.membership_package_id', 'membership_orders.order_status',
        'membership_packages.pricing', 'membership_packages.duration', 'membership_packages.is_recurring',
        'membership_packages.related_site_role_id', 'membership_packages.site_role_id_on_expire',
        'membership_orders.transaction_info',
    ];
    $join["[>]membership_packages"] = ['membership_orders.membership_package_id' => 'membership_package_id'];
    $where["membership_orders.order_id"] = $order_id;
    $where["membership_orders.user_id"] = Registry::load('current_user')->id;
    $membership_order = DB::connect()->select('membership_orders', $join, $columns, $where);

    if (isset($membership_order[0])) {
        $membership_order = $membership_order[0];

        if ((int)$membership_order['order_status'] !== 0) {
            redirect(Registry::load('config')->site_url);
        }

        if (empty($membership_order['pricing'])) {
            $order_id = null;
        } else {
            $columns = $join = $where = null;
            $columns = ['payment_gateways.identifier', 'payment_gateways.credentials'];
            $where["payment_gateways.payment_gateway_id"] = $membership_order['payment_gateway_id'];
            $where["payment_gateways.disabled[!]"] = 1;
            $gateway = DB::connect()->select('payment_gateways', $columns, $where);

            if (isset($gateway[0])) {
                $gateway = $gateway[0];

                if ($gateway['identifier'] !== 'bank_transfer') {
                    $order_id = null;
                }
            } else {
                $order_id = null;
            }

            $columns = $join = $where = null;
            $columns = [
                'bank_transfer_receipts.receipt_status',
                'bank_transfer_receipts.receipt_file_name',
                'bank_transfer_receipts.bank_transfer_receipt_id'
            ];
            $where["bank_transfer_receipts.membership_order_id"] = $order_id;
            $bank_receipt = DB::connect()->select('bank_transfer_receipts', $columns, $where);


        }
    } else {
        $order_id = null;
    }
}

if (empty($order_id)) {
    echo "Invalid Order ID";
    exit;
}