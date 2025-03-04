<?php

require 'fns/sms_gateway/messagebird/autoload.php';

$access_key = Registry::load('settings')->messagebird_api_key;

try {
    $MessageBird = new \MessageBird\Client($access_key);

    $Message = new \MessageBird\Objects\Message();
    $Message->originator = Registry::load('settings')->sms_src;
    $Message->recipients = array($data['phone_number']);
    $Message->body = $data['message'];


    $result['success'] = true;
    $result['response'] = $MessageBird->messages->create($Message);

} catch (Exception $e) {
    $result['response'] = $e->getMessage();
    $result['success'] = false;
}