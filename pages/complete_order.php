<?php
include 'fns/firewall/load.php';
include_once 'fns/sql/load.php';
include_once 'fns/SleekDB/Store.php';
include 'fns/variables/load.php';

if (Registry::load('current_user')->logged_in) {
    $domain_url_path = urldecode(Registry::load('config')->url_path);
    $domain_url_path = preg_split('/\//', $domain_url_path);
    $order_id = null;

    if (isset($domain_url_path[1])) {
        $order_id = filter_var($domain_url_path[1], FILTER_SANITIZE_NUMBER_INT);
        if (!empty($order_id)) {

            $columns = $join = $where = null;
            $gateway = array();
            $columns = [
                'membership_orders.payment_gateway_id', 'membership_orders.user_id',
                'membership_orders.membership_package_id', 'membership_orders.order_status',
                'membership_packages.pricing', 'membership_packages.duration'
            ];
            $join["[>]membership_packages"] = ['membership_orders.membership_package_id' => 'membership_package_id'];
            $where["membership_orders.order_id"] = $order_id;
            $where["membership_orders.user_id"] = Registry::load('current_user')->id;
            $membership_order = DB::connect()->select('membership_orders', $join, $columns, $where);

            if (isset($membership_order[0])) {
                $membership_order = $membership_order[0];
                $columns = $join = $where = null;
                $columns = ['payment_gateways.identifier', 'payment_gateways.credentials'];
                $where["payment_gateways.payment_gateway_id"] = $membership_order['payment_gateway_id'];
                $where["payment_gateways.disabled[!]"] = 1;
                $gateway = DB::connect()->select('payment_gateways', $columns, $where);
            }

            if (isset($membership_order['order_status']) && isset($gateway[0])) {
                $gateway = $gateway[0];
                $validation_url = Registry::load('config')->site_url.'validate_order/'.$order_id.'/';

                if ((int)$membership_order['order_status'] === 0) {

                    include_once 'fns/payments/load.php';

                    $package_name = 'membership_package_'.$membership_order['membership_package_id'];
                    $package_name = Registry::load('strings')->$package_name;

                    $description = Registry::load('strings')->order_id.': '.$order_id;
                    $description .= ' '.Registry::load('strings')->package_name.': '.$package_name;
                    $description .= ' ['.Registry::load('settings')->site_name.']';

                    $payment_data = [
                        'gateway' => $gateway['identifier'],
                        'transactionId' => $order_id,
                        'purchase' => $membership_order['pricing'],
                        'package_name' => $package_name,
                        'membership_package_id' => $membership_order['membership_package_id'],
                        'credentials' => $gateway['credentials'],
                        'description' => $description,
                        'validation_url' => $validation_url,
                    ];


                    $transaction_info = $payment_data;
                    $transaction_info['pricing'] = $payment_data['purchase'];

                    $columns = $join = $where = null;
                    $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
                    $where["billing_address.user_id"] = Registry::load('current_user')->id;
                    $billing_address = DB::connect()->select('billing_address', $columns, $where);

                    if (!empty($billing_address)) {
                        $transaction_info['billing_info'] = $billing_address[0];
                    }

                    $transaction_info = json_encode($transaction_info);
                    DB::connect()->update('membership_orders', ['transaction_info' => $transaction_info], ['order_id' => $order_id]);


                    payment_module($payment_data);

                } else if ((int)$membership_order['order_status'] === 1) {
                    redirect(Registry::load('config')->site_url.'membership_packages/');
                } else if ((int)$membership_order['order_status'] === 3) {
                    redirect($validation_url);
                } else if ((int)$membership_order['order_status'] === 2) {
                    redirect(Registry::load('config')->site_url.'membership_packages/');
                }
            } else {
                $order_id = null;
            }
        }
    }

    if (empty($order_id)) {
        echo "Failed Transcation. Error : Invalid Order ID.";
    }
} else {
    redirect('404');
}

?>