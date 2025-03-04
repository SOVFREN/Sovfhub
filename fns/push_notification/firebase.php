<?php

require 'fns/push_notification/firebase_php/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

if (!empty(Registry::load('settings')->firebase_private_key)) {

    $firebase_pkey = 'fns/push_notification/firebase_key/'.Registry::load('settings')->firebase_private_key;

    if (file_exists($firebase_pkey)) {
        $factory = (new Factory)
        ->withServiceAccount($firebase_pkey);

        $messaging = $factory->createMessaging();
        $data['link'] = Registry::load('config')->site_url;

        $notification = Notification::create($data['title'], $data['message']);
        $message = CloudMessage::new()->withNotification($notification);

        try {
            $sendReport = $messaging->sendMulticast($message, $data['device_tokens']);
            $response = "Notification sent successfully!";
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            $response = "Error sending notification: " . $e->getMessage();
        }

        $result = array();
        $result['response'] = $response;
    }
}