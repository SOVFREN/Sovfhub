<?php
$result = array();
$result['alert'] = Registry::load('strings')->went_wrong;

if (Registry::load('settings')->memberships === 'enable') {
    if (role(['permissions' => ['memberships' => 'enroll_membership']])) {

        if (isset($data['payment_gateway_id']) && isset($data['membership_package_id'])) {
            if (!empty($data['payment_gateway_id']) && !empty($data['membership_package_id'])) {

                $columns = $join = $where = null;
                $columns = ['membership_packages.membership_package_id', 'membership_packages.pricing'];
                $where["membership_packages.membership_package_id"] = $data['membership_package_id'];
                $where["membership_packages.disabled[!]"] = 1;
                $package = DB::connect()->select('membership_packages', $columns, $where);

                $columns = $join = $where = null;
                $columns = ['payment_gateways.payment_gateway_id'];
                $where["payment_gateways.payment_gateway_id"] = $data['payment_gateway_id'];
                $where["payment_gateways.disabled[!]"] = 1;
                $gateway = DB::connect()->select('payment_gateways', $columns, $where);

                $columns = $join = $where = null;
                $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
                $where["billing_address.user_id"] = Registry::load('current_user')->id;
                $billing_address = DB::connect()->select('billing_address', $columns, $where);

                if (Registry::load('settings')->require_billing_address === 'yes' && empty($billing_address)) {
                    $result['alert'] = Registry::load('strings')->billing_address_not_found;
                    return;
                }

                $free_package = false;

                if (!empty($package)) {
                    if (empty($package[0]['pricing'])) {
                        $free_package = true;
                        $data['payment_gateway_id'] = null;
                    }
                }

                if (!empty($package) && !empty($gateway) || $free_package) {

                    DB::connect()->insert("membership_orders", [
                        "user_id" => Registry::load('current_user')->id,
                        "membership_package_id" => $data['membership_package_id'],
                        "payment_gateway_id" => $data['payment_gateway_id'],
                        "created_on" => Registry::load('current_user')->time_stamp,
                        "updated_on" => Registry::load('current_user')->time_stamp,
                    ]);

                    if (!DB::connect()->error) {
                        $membership_order_id = DB::connect()->id();

                        $result = array();

                        if ($free_package) {
                            $result['redirect'] = Registry::load('config')->site_url.'validate_order/'.$membership_order_id.'/';
                        } else {
                            $result['redirect'] = Registry::load('config')->site_url.'complete_order/'.$membership_order_id.'/';
                        }

                    }
                }

            }
        }
    }
}
?>