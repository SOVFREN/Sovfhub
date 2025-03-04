<?php

require_once 'fns/dompdf/autoload.php';
require 'fns/template_engine/latte.php';

use Dompdf\Dompdf;
use Dompdf\Options;


$file_name = '';

if (role(['permissions' => ['memberships' => 'download_invoice']])) {

    if (isset($download["order_id"])) {

        $columns = [
            'membership_orders.order_id', 'membership_orders.user_id',
            'membership_orders.membership_package_id',
            'membership_orders.order_status',
            'membership_orders.payment_gateway_id',
            'membership_orders.transaction_info',
            'membership_orders.created_on',
        ];


        if (!role(['permissions' => ['memberships' => 'view_site_transactions']])) {
            $where["membership_orders.user_id"] = Registry::load('current_user')->id;
        }

        $where["membership_orders.order_status"] = 1;
        $where["membership_orders.order_id"] = $download["order_id"];
        $where["LIMIT"] = 1;
        $membership_order = DB::connect()->select('membership_orders', $columns, $where);

        if (!empty($membership_order)) {
            if (isset($download['validate'])) {
                $output = array();
                $output['success'] = true;
                $output['download_link'] = Registry::load('config')->site_url.'download/invoice/';
                $output['download_link'] .= 'order_id/'.$download['order_id'].'/';
            } else {
                $membership_order = $membership_order[0];
                $options = new Options();
                $options->set('isRemoteEnabled', true);
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isPhpEnabled', true);
                $dompdf = new Dompdf($options);

                $transaction_info = $membership_order['transaction_info'];

                if (!empty($transaction_info)) {
                    $transaction_info = json_decode($transaction_info);
                }

                $template_variables = array();
                $template_variables['site_url'] = Registry::load('config')->site_url;
                $template_variables['invoice'] = Registry::load('strings')->invoice;
                $template_variables['invoice_from'] = Registry::load('strings')->invoice_from;
                $template_variables['invoice_to'] = Registry::load('strings')->invoice_to;

                $template_variables['invoice_title'] = 'INVOICE_'.$membership_order['order_id'];


                $template_variables['billed_to'] = 'Client Name';
                $template_variables['client_address'] = 'Street Address<br>State, City<br>Country - PIN1234';

                if (!empty($transaction_info)) {
                    if (isset($transaction_info->billing_info)) {
                        $billing_info = $transaction_info->billing_info;

                        $billing_info->country = str_replace('-', ' ', $billing_info->country);
                        $billing_info->country = ucwords($billing_info->country);

                        $template_variables['billed_to'] = $billing_info->billed_to;
                        $template_variables['client_address'] = nl2br($billing_info->street_address).'<br>';
                        $template_variables['client_address'] .= $billing_info->city.', '.$billing_info->state.'<br>';
                        $template_variables['client_address'] .= $billing_info->country.'<br>'.$billing_info->postal_code;
                    }
                }

                $template_variables['billed_from'] = Registry::load('settings')->invoice_from;
                $template_variables['business_address'] = nl2br(Registry::load('settings')->company_address);

                $template_variables['invoice_id_text'] = Registry::load('strings')->invoice_id;
                $template_variables['invoice_id'] = $membership_order['order_id'];

                $template_variables['order_id_text'] = '#';
                $template_variables['description_text'] = Registry::load('strings')->package_name;
                $template_variables['price_text'] = Registry::load('strings')->price;
                $template_variables['date_text'] = Registry::load('strings')->date_text;
                $template_variables['invoice_total'] = Registry::load('strings')->invoice_total;

                $package_name = 'membership_package_'.$membership_order['membership_package_id'];

                if (!isset(Registry::load('strings')->$package_name)) {
                    $package_name = 'unknown';
                }

                $package_name = Registry::load('strings')->$package_name;

                $template_variables['order_id'] = 1;
                $template_variables['order_description'] = $package_name;

                $template_variables['order_price'] = Registry::load('settings')->default_currency_symbol.$transaction_info->pricing;

                $order_date['date'] = $membership_order['created_on'];
                $order_date['auto_format'] = true;
                $order_date['include_time'] = true;
                $order_date['timezone'] = Registry::load('current_user')->time_zone;
                $template_variables['order_date'] = get_date($order_date)['date'];
                $template_variables['payment_method_text'] = $template_variables['payment_method_image'] = '';

                $template_variables['invoice_footer_note'] = Registry::load('settings')->invoice_footer;

                if (isset($transaction_info->gateway) && !empty($transaction_info->gateway)) {
                    $image_url = Registry::load('config')->site_url;
                    $image_url = $image_url.'assets/files/payment_gateways/light/'.$transaction_info->gateway.'.png';

                    $template_variables['payment_method_text'] = Registry::load('strings')->payment_method;
                    $template_variables['payment_method_image'] = '<img src="'.$image_url.'"/>';
                }

                $template = new Latte\Engine;
                $html = $template->renderToString('fns/download/template_invoice.php', $template_variables);

                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $dompdf->stream('membership_invoice.pdf', array('Attachment' => 0));

                exit;
            }
        }
    }
}

if (!isset($output['download_link'])) {
    $output['error'] = Registry::load('strings')->something_went_wrong;
}

?>