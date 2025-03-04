<?php
include 'fns/firewall/load.php';
include_once 'fns/sql/load.php';
include_once 'fns/SleekDB/Store.php';
include 'fns/variables/load.php';

use SleekDB\Store;


$domain_url_path = urldecode(Registry::load('config')->url_path);
$domain_url_path = preg_split('/\//', $domain_url_path);
$order_id = null;

if (isset($domain_url_path[1])) {
    $order_id = filter_var($domain_url_path[1], FILTER_SANITIZE_NUMBER_INT);
}

if (isset($_POST['error']) && isset($_POST['error']['code']) || isset($_POST['razorpay_payment_id'])|| isset($_POST['token'])) {
    $validation_url = Registry::load('config')->site_url.'validate_order/'.$order_id.'/';
    ?>
    <script>
        window.setTimeout(function() {
            window.location.href = "<?php echo $validation_url ?>";
        }, 500);
    </script>
    <?php
    exit;
}

if (Registry::load('current_user')->logged_in) {
    $free_package = false;

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

            if (empty($membership_order['pricing'])) {
                $free_package = true;
            }

            if ($free_package) {
                $gateway[0]['identifier'] = '';
            } else {
                $columns = $join = $where = null;
                $columns = ['payment_gateways.identifier', 'payment_gateways.credentials'];
                $where["payment_gateways.payment_gateway_id"] = $membership_order['payment_gateway_id'];
                $where["payment_gateways.disabled[!]"] = 1;
                $gateway = DB::connect()->select('payment_gateways', $columns, $where);
            }
        }

        if (isset($membership_order['order_status']) && isset($gateway[0])) {
            $gateway = $gateway[0];
            $validation_url = Registry::load('config')->site_url.'validate_order/'.$order_id.'/';

            if ((int)$membership_order['order_status'] === 0) {
                $package_name = 'membership_package_'.$membership_order['membership_package_id'];
                $package_name = Registry::load('strings')->$package_name;

                $description = Registry::load('strings')->order_id.': '.$order_id;
                $description .= ' '.Registry::load('strings')->package_name.': '.$package_name;

                $payment_data = [
                    'transactionId' => $order_id,
                    'gateway' => $gateway['identifier'],
                    'pricing' => $membership_order['pricing'],
                    'duration' => $membership_order['duration'],
                    'description' => $description,
                ];

                if ($free_package) {
                    $payment_status['success'] = true;
                    $payment_status['transaction_info'] = ['free_package' => true];
                } else {
                    include_once 'fns/payments/load.php';

                    $validate_data = [
                        'validate_purchase' => $order_id,
                        'gateway' => $gateway['identifier'],
                        'credentials' => $gateway['credentials']
                    ];

                    if (!empty($membership_order['transaction_info'])) {
                        $payment_session_data = json_decode($membership_order['transaction_info']);

                        if (!empty($payment_session_data)) {
                            if (isset($payment_session_data->payment_session_id)) {
                                $validate_data['payment_session_id'] = $payment_session_data->payment_session_id;
                            } else if (isset($payment_session_data->payment_session_data)) {
                                $validate_data['payment_session_data'] = $payment_session_data->payment_session_data;
                            }
                        }
                    }

                    $payment_status = payment_module($validate_data);
                }

                $transaction_info = array_merge($payment_data, $payment_status['transaction_info']);

                if ($payment_status['success']) {
                    $order_status = 1;
                } else {
                    $order_status = 2;
                    $transaction_info['error_message'] = $payment_status['error'];
                }

                $columns = $join = $where = null;
                $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
                $where["billing_address.user_id"] = Registry::load('current_user')->id;
                $billing_address = DB::connect()->select('billing_address', $columns, $where);

                if (!empty($billing_address)) {
                    $transaction_info['billing_info'] = $billing_address[0];
                } else {
                    $transaction_info['billing_info']['billed_to'] = Registry::load('current_user')->name;
                    $transaction_info['billing_info']['street_address'] = $transaction_info['billing_info']['city'] = '';
                    $transaction_info['billing_info']['country'] = $transaction_info['billing_info']['state'] = '';
                    $transaction_info['billing_info']['postal_code'] = '';
                }

                $transaction_info = json_encode($transaction_info);

                DB::connect()->update('membership_orders', ['order_status' => $order_status, 'transaction_info' => $transaction_info], ['order_id' => $order_id]);

                if ($payment_status['success']) {

                    $non_expiring = 0;

                    if (!empty($membership_order['is_recurring'])) {
                        $non_expiring = 1;
                        $expiring_on = Registry::load('current_user')->time_stamp;
                    } else {
                        $duration = 1;

                        if (!empty($membership_order['duration'])) {
                            $duration = $membership_order['duration'];
                        }

                        $expiring_on = Registry::load('current_user')->time_stamp;
                        $expiring_on = strtotime($expiring_on);
                        $expiring_on = strtotime('+'.$duration.' days', $expiring_on);
                        $expiring_on = date('Y-m-d H:i:s', $expiring_on);
                    }

                    $membership_data = [
                        'user_id' => Registry::load('current_user')->id,
                        'membership_package_id' => $membership_order['membership_package_id'],
                        'started_on' => Registry::load('current_user')->time_stamp,
                        'expiring_on' => $expiring_on,
                        'non_expiring' => $non_expiring,
                        'updated_on' => Registry::load('current_user')->time_stamp,
                    ];

                    $user_membership = DB::connect()->select('site_users_membership',
                        ['site_users_membership.membership_info_id'],
                        ['site_users_membership.user_id' => Registry::load('current_user')->id]
                    );

                    if (isset($user_membership[0])) {
                        DB::connect()->update('site_users_membership', $membership_data,
                            ['site_users_membership.user_id' => Registry::load('current_user')->id]);
                    } else {
                        DB::connect()->insert('site_users_membership', $membership_data);
                    }

                    $membership_logs = new Store('membership_logs', 'assets/nosql_database/');
                    $membership_data["_id"] = Registry::load('current_user')->id;
                    $membership_data["site_role_id_on_expire"] = $membership_order['site_role_id_on_expire'];
                    $membership_logs->updateOrInsert($membership_data, false);

                    $related_site_role_id = $membership_order['related_site_role_id'];

                    DB::connect()->update('site_users', ['site_role_id' => $related_site_role_id],
                        ['site_users.user_id' => Registry::load('current_user')->id]);


                    $layout_variable = array();
                    $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->success;
                    $layout_variable['description'] = Registry::load('strings')->transaction_successful_message;
                    $layout_variable['button'] = Registry::load('strings')->continue_text;
                    $layout_variable['successful'] = true;
                    include_once 'layouts/transaction_status/layout.php';
                    exit;

                } else {
                    $layout_variable = array();
                    $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->failed;
                    $layout_variable['description'] = Registry::load('strings')->transaction_failed_message;
                    $layout_variable['button'] = Registry::load('strings')->continue_text;
                    $layout_variable['successful'] = false;

                    include_once 'layouts/transaction_status/layout.php';
                    exit;
                }


            } else {

                $layout_variable = array();
                $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->failed;
                $layout_variable['description'] = Registry::load('strings')->transaction_failed_message;
                $layout_variable['button'] = Registry::load('strings')->continue_text;
                $layout_variable['successful'] = false;

                include_once 'layouts/transaction_status/layout.php';
                exit;
            }

        } else {
            $order_id = null;
        }

    }

    if (empty($order_id)) {
        $layout_variable = array();
        $layout_variable['title'] = $layout_variable['status'] = Registry::load('strings')->failed;
        $layout_variable['description'] = Registry::load('strings')->invalid_order_id;
        $layout_variable['button'] = Registry::load('strings')->continue_text;
        $layout_variable['successful'] = false;

        include_once 'layouts/transaction_status/layout.php';
        exit;
    }
} else {
    redirect('404');
}

?>