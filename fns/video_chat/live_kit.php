<?php

include ('fns/live_kit_sdk/autoload.php');

use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;
use Agence104\LiveKit\AccessToken;

if (isset($data['generate_token']) && !empty($data['generate_token'])) {

    if (isset($data['generate_token']['channel_name']) && !empty($data['generate_token']['channel_name'])) {


        $channelName = $data['generate_token']['channel_name'];

        $live_kit_api_key = Registry::load('settings')->live_kit_api_key;
        $live_kit_secret_key = Registry::load('settings')->live_kit_secret_key;
        $live_kit_url = Registry::load('settings')->live_kit_url;

        $participantName = Registry::load('current_user')->id;

        $tokenOptions = (new AccessTokenOptions())->setIdentity($participantName);

        $videoGrant = (new VideoGrant())->setRoomJoin()->setRoomName($channelName);

        $token = new AccessToken($live_kit_api_key, $live_kit_secret_key);
        $token = $token->init($tokenOptions)->setGrant($videoGrant)->toJwt();


        $result = array();
        $result['token'] = $token;
        $result['channel'] = $channelName;
        $result['live_kit_url'] = $live_kit_url;
    }
}

?>