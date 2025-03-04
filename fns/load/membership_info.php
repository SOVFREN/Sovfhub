<?php
use SleekDB\Store;

$output = array();
$index = 1;
$language_id = Registry::load('current_user')->language;

if (Registry::load('settings')->memberships === 'enable') {
    if (role(['permissions' => ['memberships' => 'view_membership_info']])) {
        $columns = $join = $where = null;
        $columns = [
            'site_users_membership.membership_package_id', 'site_users_membership.expiring_on',
            'site_users_membership.started_on', 'site_users_membership.non_expiring'
        ];
        $where['site_users_membership.user_id'] = Registry::load('current_user')->id;
        $user_membership = DB::connect()->select('site_users_membership', $columns, $where);

        if (isset($user_membership[0]) && !empty($user_membership[0]['membership_package_id'])) {
            $user_membership = $user_membership[0];
            $package_name = 'membership_package_'.$user_membership['membership_package_id'];

            $output['info_items'][$index] = new stdClass();
            $output['info_items'][$index]->title = Registry::load('strings')->package_name.": ";
            $output['info_items'][$index]->value = Registry::load('strings')->$package_name;
            $index++;

            $started_on['date'] = $user_membership['started_on'];
            $started_on['auto_format'] = true;

            $output['info_items'][$index] = new stdClass();
            $output['info_items'][$index]->title = Registry::load('strings')->started_on.': ';
            $output['info_items'][$index]->value = get_date($started_on);
            $index++;

            $expiring_on['date'] = $user_membership['expiring_on'];
            $expiring_on['auto_format'] = true;

            $output['info_items'][$index] = new stdClass();
            $output['info_items'][$index]->title = Registry::load('strings')->expiring_on.': ';

            $timestamp = strtotime($user_membership['expiring_on']);
            $current_timestamp = time();

            if ($timestamp < $current_timestamp) {
                $output['info_items'][$index]->title = Registry::load('strings')->expired_on.': ';
            }

            if (!empty($user_membership['non_expiring'])) {
                $output['info_items'][$index]->value = Registry::load('strings')->lifetime;
            } else {
                $output['info_items'][$index]->value = get_date($expiring_on);
            }

            $index++;


        }
    }


    $columns = $join = $where = null;
    $columns = [
        'membership_packages.membership_package_id', 'membership_packages.string_constant', 'membership_packages.is_recurring',
        'membership_packages.pricing', 'membership_packages.duration'
    ];
    $where["membership_packages.disabled[!]"] = 1;
    $where["ORDER"] = ["membership_packages.package_sort_index" => "ASC", "membership_packages.membership_package_id" => "DESC"];
    $packages = DB::connect()->select('membership_packages', $columns, $where);

    $index = 1;

    foreach ($packages as $package) {
        $string_constant = $package['string_constant'];
        $output['packages'][$index] = new stdClass();
        $output['packages'][$index]->membership_package_id = $package['membership_package_id'];
        $output['packages'][$index]->title = Registry::load('strings')->$string_constant;
        $output['packages'][$index]->pricing = Registry::load('settings')->default_currency_symbol.$package['pricing'];

        if (role(['permissions' => ['memberships' => 'enroll_membership']])) {
            $output['packages'][$index]->purchase_button = Registry::load('strings')->select_plan;
        }

        if (!empty($package["is_recurring"])) {
            $output['packages'][$index]->duration = Registry::load('strings')->duration.': '.Registry::load('strings')->lifetime;
        } else {
            $output['packages'][$index]->duration = Registry::load('strings')->duration.': '.$package['duration'].' '.Registry::load('strings')->days;
        }

        $no_sql = new Store('membership_package_benefits', 'assets/nosql_database/');
        $benefits = $no_sql->findById($package["membership_package_id"]);

        if (!empty($benefits)) {
            $language_index = 'language_'.$language_id;
            if (isset($benefits[$language_index])) {
                $output['packages'][$index]->benefits = $benefits[$language_index];
            }
        }


        $index++;
    }

    $columns = $join = $where = null;
    $columns = ['payment_gateways.payment_gateway_id', 'payment_gateways.identifier'];
    $where["payment_gateways.disabled[!]"] = 1;
    $payment_gateways = DB::connect()->select('payment_gateways', $columns, $where);

    foreach ($payment_gateways as $payment_gateway) {
        $index = $payment_gateway['payment_gateway_id'];
        $output['payment_gateways'][$index] = $payment_gateway['identifier'];
    }
}
?>