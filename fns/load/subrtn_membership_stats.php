<?php

use Medoo\Medoo;

$index = 1;

$output = array();

$child_index = 0;
$output['module'][$index] = new stdClass();
$output['module'][$index]->type = 'numbers';

$items = array();
$items[$child_index]['title'] = Registry::load('strings')->packages;
$items[$child_index]['result'] = DB::connect()->count('membership_packages');
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'membership_packages',
    'role' => 'button'
];
$child_index++;

$items[$child_index]['title'] = Registry::load('strings')->total_orders;
$items[$child_index]['result'] = DB::connect()->count('membership_orders');
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;

$items[$child_index]['title'] = Registry::load('strings')->successful_orders;
$items[$child_index]['result'] = DB::connect()->count('membership_orders', ["order_status" => 1]);
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;

$where = ["order_status" => 1, 'created_on[>]' => date('Y-m-d')];
$items[$child_index]['title'] = Registry::load('strings')->today;
$items[$child_index]['result'] = DB::connect()->count('membership_orders', $where);
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;


$where = Medoo::raw('WHERE order_status = 1 AND DATE(created_on) = DATE(NOW() - INTERVAL 1 DAY)');
$yesterday_orders = DB::connect()->count('membership_orders', $where);

$items[$child_index]['title'] = Registry::load('strings')->yesterday;
$items[$child_index]['result'] = $yesterday_orders;
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;

$Month = date('m');
$Year = date('Y');
$where = Medoo::raw("WHERE order_status = 1 AND MONTH(created_on) = '".$Month."' AND YEAR(created_on) = '".$Year."'");
$this_month = DB::connect()->count('membership_orders', $where);

$items[$child_index]['title'] = Registry::load('strings')->this_month;
$items[$child_index]['result'] = $this_month;
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;

$Month = date('m', strtotime('last month'));
$Year = date('Y');
$where = Medoo::raw("WHERE order_status = 1 AND MONTH(created_on) = '".$Month."' AND YEAR(created_on) = '".$Year."'");
$last_month = DB::connect()->count('membership_orders', $where);

$items[$child_index]['title'] = Registry::load('strings')->last_month;
$items[$child_index]['result'] = $last_month;
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;


$Year = date('Y');
$where = Medoo::raw("WHERE order_status = 1 AND YEAR(created_on) = '".$Year."'");
$this_year = DB::connect()->count('membership_orders', $where);

$items[$child_index]['title'] = Registry::load('strings')->this_year;
$items[$child_index]['result'] = $this_year;
$items[$child_index]['attributes'] = [
    'class' => 'load_aside',
    'load' => 'site_transactions',
    'role' => 'button'
];
$child_index++;




$output['module'][$index]->items = $items;
$index++;



$output['module'][$index] = new stdClass();
$output['module'][$index]->title = Registry::load('strings')->recent_transactions;
$output['module'][$index]->type = 'list';

$child_index = 0;
$items = array();

$columns = $where = $join = null;
$columns = [
    'membership_orders.order_id', 'membership_orders.user_id',
    'membership_orders.membership_package_id',
    'membership_orders.order_status',
    'membership_orders.payment_gateway_id',
    'membership_orders.transaction_info',
    'membership_orders.created_on',
    'site_users.username', 'site_users.display_name'
];

$join["[>]site_users"] = ["membership_orders.user_id" => "user_id"];

$where["ORDER"] = ["membership_orders.order_id" => "DESC"];
$where["LIMIT"] = 20;

$membership_orders = DB::connect()->select('membership_orders', $join, $columns, $where);

foreach ($membership_orders as $order) {

    $created_on = array();
    $created_on['date'] = $order['created_on'];
    $created_on['auto_format'] = true;
    $created_on['include_time'] = true;
    $created_on['timezone'] = Registry::load('current_user')->time_zone;
    $created_on = get_date($created_on);


    if ((int)$order['order_status'] === 1) {
        $order_status = Registry::load('strings')->successful;
        $order_status_img = Registry::load('config')->site_url."assets/files/defaults/successful.png";
    } else if ((int)$order['order_status'] === 0) {
        $order_status = Registry::load('strings')->pending;
        $order_status_img = Registry::load('config')->site_url."assets/files/defaults/pending.png";
    } else {
        $order_status = Registry::load('strings')->failed;
        $order_status_img = Registry::load('config')->site_url."assets/files/defaults/failed.png";
    }

    $items[$child_index] = new stdClass();
    $items[$child_index]->items[1]['type'] = 'image';
    $items[$child_index]->items[1]['image'] = $order_status_img;

    $items[$child_index]->items[2]['type'] = 'info';
    $items[$child_index]->items[2]['bold_text'] = $order['display_name'];
    $items[$child_index]->items[2]['text'] = $order_status;

    $package_name = 'membership_package_'.$order['membership_package_id'];

    if (!isset(Registry::load('strings')->$package_name)) {
        $package_name = 'unknown';
    }

    $items[$child_index]->items[3]['type'] = 'info';
    $items[$child_index]->items[3]['text'] = Registry::load('strings')->$package_name;

    $items[$child_index]->items[4]['type'] = 'info';
    $items[$child_index]->items[4]['text'] = $created_on['date'].'<br>'.$created_on['time'];

    $items[$child_index]->items[5]['type'] = 'button';
    $items[$child_index]->items[5]['text'] = Registry::load('strings')->view;
    $items[$child_index]->items[5]['attributes']['class'] = 'load_form';
    $items[$child_index]->items[5]['attributes']['form'] = 'transactions';
    $items[$child_index]->items[5]['attributes']['data-order_id'] = $order['order_id'];
    $child_index++;
}


$output['module'][$index]->items = $items;

$index++;

?>