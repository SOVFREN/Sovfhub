<?php
require('fns/ip_intelligence/proxycheck/autoload.php');

$banOnProbability = Registry::load('settings')->ip_intelligence_probability;

if (empty($banOnProbability)) {
    $banOnProbability = 50;
}

$proxycheck_options = array(
    'VPN_DETECTION' => 1,
    'ASN_DATA' => 1,
    'RISK_DATA' => 1,
);

if (!empty(Registry::load('settings')->ip_intelligence_api_key)) {
    $proxycheck_options['API_KEY'] = Registry::load('settings')->ip_intelligence_api_key;
}

$response = \proxycheck\proxycheck::check($ip_address, $proxycheck_options);

if (isset($response['block']) && $response['block'] == 'yes') {
    $result['success'] = false;
    $result['response'] = $response;
} else if (isset($response[$ip_address]['risk'])) {

    $score = $response[$ip_address]['risk'];

    if ((int)$score < (int)$banOnProbability) {
        $result['success'] = true;
    } else {
        $result['success'] = false;
        $result['response'] = $response;
    }
}
?>