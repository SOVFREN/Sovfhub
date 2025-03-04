<?php

require 'fns/sms_gateway/Twilio/autoload.php';

$sid = Registry::load('settings')->twilio_account_sid;
$token = Registry::load('settings')->twilio_auth_token;

try {
    $client = new Twilio\Rest\Client($sid, $token);

    $result['success'] = true;
    $result['response'] = $client->messages->create(
        $data['phone_number'],
        [
            'from' => Registry::load('settings')->sms_src,
            'body' => $data['message']
        ]
    );
} catch (Exception $e) {
    $result['response'] = $e->getMessage();
    $result['success'] = false;
}