<?php

if (role(['permissions' => ['bank_transfer_receipts' => 'view']])) {

    $form = array();
    $form['loaded'] = new stdClass();
    $form['fields'] = new stdClass();

    if (isset($load["bank_transfer_receipt_id"])) {

        $load["bank_transfer_receipt_id"] = filter_var($load["bank_transfer_receipt_id"], FILTER_SANITIZE_NUMBER_INT);

        if (!empty($load['bank_transfer_receipt_id'])) {

            $columns = [
                'bank_transfer_receipts.bank_transfer_receipt_id', 'bank_transfer_receipts.membership_order_id',
                'bank_transfer_receipts.receipt_status', 'bank_transfer_receipts.updated_on', 'site_users.display_name',
                'membership_orders.membership_package_id', 'membership_packages.pricing', 'bank_transfer_receipts.receipt_file_name',
            ];

            $join["[>]membership_orders"] = ["bank_transfer_receipts.membership_order_id" => "order_id"];
            $join["[>]membership_packages"] = ["membership_orders.membership_package_id" => "membership_package_id"];
            $join["[>]site_users"] = ["membership_orders.user_id" => "user_id"];

            $where["bank_transfer_receipts.bank_transfer_receipt_id"] = $load["bank_transfer_receipt_id"];
            $where["LIMIT"] = 1;

            $bank_receipt = DB::connect()->select('bank_transfer_receipts', $join, $columns, $where);

            if (isset($bank_receipt[0])) {

                $bank_receipt = $bank_receipt[0];

                $package_name = 'membership_package_'.$bank_receipt['membership_package_id'];

                if (!isset(Registry::load('strings')->$package_name)) {
                    $package_name = 'unknown';
                }

                $form['loaded']->title = Registry::load('strings')->bank_receipts;

                if (role(['permissions' => ['bank_transfer_receipts' => 'validate']])) {
                    $form['loaded']->button = Registry::load('strings')->update;
                }

                $form['fields']->bank_transfer_receipt_id = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => $load["bank_transfer_receipt_id"]
                ];

                $form['fields']->update = [
                    "tag" => 'input', "type" => 'hidden', "class" => 'd-none', "value" => "bank_transfer_receipts"
                ];

                $form['fields']->full_name = [
                    "title" => Registry::load('strings')->full_name, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $bank_receipt['display_name'], "attributes" => ['disabled' => 'disabled']
                ];

                $form['fields']->order_id = [
                    "title" => Registry::load('strings')->order_id, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $bank_receipt['membership_order_id'], "attributes" => ['disabled' => 'disabled']
                ];


                $bank_receipt_file = Registry::load('config')->site_url.'assets/files/bank_receipts/'.$bank_receipt['receipt_file_name'];

                $form['fields']->bank_receipt = [
                    "title" => Registry::load('strings')->bank_receipt, "tag" => 'link', "type" => 'external_link',
                    "text" => Registry::load('strings')->view_receipt, "link" => $bank_receipt_file, "class" => 'field',
                    "link_target" => "_blank"
                ];

                if (role(['permissions' => ['bank_transfer_receipts' => 'validate']])) {
                    $form['fields']->take_action = [
                        "title" => Registry::load('strings')->take_action, "tag" => 'select', "class" => 'field',
                    ];
                    $form['fields']->take_action['options'] = [
                        "approve" => Registry::load('strings')->approve,
                        "approve_enroll" => Registry::load('strings')->approve_enroll,
                        "disapprove" => Registry::load('strings')->disapprove,
                    ];
                }


                $form['fields']->membership_package_id = [
                    "title" => Registry::load('strings')->membership_package_id, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $bank_receipt['membership_package_id'], "attributes" => ['disabled' => 'disabled']
                ];

                $form['fields']->package_name = [
                    "title" => Registry::load('strings')->package_name, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => Registry::load('strings')->$package_name, "attributes" => ['disabled' => 'disabled']
                ];

                $bank_receipt['pricing'] = Registry::load('settings')->default_currency_symbol.''.$bank_receipt['pricing'];

                $form['fields']->pricing = [
                    "title" => Registry::load('strings')->pricing, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $bank_receipt['pricing'], "attributes" => ['disabled' => 'disabled']
                ];


                $uploaded_on = array();
                $uploaded_on['date'] = $bank_receipt['updated_on'];
                $uploaded_on['auto_format'] = true;
                $uploaded_on['include_time'] = true;
                $uploaded_on['timezone'] = Registry::load('current_user')->time_zone;
                $uploaded_on = get_date($uploaded_on);

                $form['fields']->uploaded_on = [
                    "title" => Registry::load('strings')->uploaded_on, "tag" => 'input', "type" => "text", "class" => 'field',
                    "value" => $uploaded_on['date'].' '.$uploaded_on['time'], "attributes" => ['disabled' => 'disabled']
                ];

            }
        }
    }
}
?>