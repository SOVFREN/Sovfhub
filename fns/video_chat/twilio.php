<?php

require 'fns/sms_gateway/Twilio/autoload.php';

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;

if (isset($data['generate_token']) && !empty($data['generate_token'])) {

    if (isset($data['generate_token']['channel_name']) && !empty($data['generate_token']['channel_name'])) {


        $channelName = $data['generate_token']['channel_name'];

        $accountSid = Registry::load('settings')->vc_twilio_account_sid;
        $authToken = Registry::load('settings')->vc_twilio_auth_token;

        $api_key = Registry::load('settings')->vc_twilio_api_key;
        $twilioApiKeySecret = Registry::load('settings')->vc_twilio_api_secret_key;

        $twilio = new Client($accountSid, $authToken);

        $identity = Registry::load('current_user')->id;
        
        $room_type = 'group';

        if (isset($data['one_to_one']) && $data['one_to_one']) {
            $room_type = 'go';
        }

        try {
            $room = $twilio->video->v1->rooms
            ->create([
                "uniqueName" => $channelName,
                "type" => $room_type
            ]);
        } catch (RestException $e) {
            $result['room_creation_error'] = $e->getMessage();
        }



        $token = new AccessToken(
            $accountSid,
            $api_key,
            $twilioApiKeySecret,
            21600,
            $identity
        );

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($channelName);

        $token->addGrant($videoGrant);


        $result = array();
        $result['token'] = $token->toJwt();
        $result['channel'] = $channelName;
    }
}
?>