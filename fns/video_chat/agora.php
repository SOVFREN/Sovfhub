<?php

if (isset($data['generate_token']) && !empty($data['generate_token'])) {

    if (isset($data['generate_token']['channel_name']) && !empty($data['generate_token']['channel_name'])) {

        require_once('fns/video_chat/AgoraDynamicKey/RtcTokenBuilder.php');

        $appID = Registry::load('settings')->vc_agora_app_id;
        $appCertificate = Registry::load('settings')->vc_agora_app_certificate;


        $channelName = $data['generate_token']['channel_name'];
        
        $uid = Registry::load('current_user')->id;

        $role = RtcTokenBuilder::RoleAttendee;
        $expireTimeInSeconds = 21600;
        $currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;


        if (isset($data['generate_token']['channel_admin']) && $data['generate_token']['channel_admin']) {
            $role = RtcTokenBuilder::RolePublisher;
        }

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

        $result = array();
        $result['token'] = $token;
        $result['channel'] = $channelName;
        $result['app_id'] = $appID;
        $result['uid'] = $uid;
    }
}
?>