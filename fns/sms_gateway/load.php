<?php

function send_sms($data) {

    $result = array();
    $result['success'] = false;

    if (!empty(Registry::load('settings')->sms_gateway) && Registry::load('settings')->sms_gateway !== 'disable') {

        $sms_gateway = Registry::load('settings')->sms_gateway;

        if (isset($data['message']) && isset($data['phone_number'])) {

            if (isset($sms_gateway) && !empty($sms_gateway)) {
                $load_fn_file = 'fns/sms_gateway/'.$sms_gateway.'.php';
                if (file_exists($load_fn_file)) {
                    include($load_fn_file);
                }
            }
        }

    }
    return $result;
}