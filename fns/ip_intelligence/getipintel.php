<?php


$contactEmail = Registry::load('settings')->system_email_address;
$timeout = 8;
$banOnProbability = Registry::load('settings')->ip_intelligence_probability;

if (empty($banOnProbability) || (float)$banOnProbability > 2) {
    $banOnProbability = 0.99;
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

curl_setopt($curl, CURLOPT_URL, "http://check.getipintel.net/check.php?ip=$ip_address&contact=$contactEmail&format=json");
$response = curl_exec($curl);
curl_close($curl);

$response = json_decode($response);

if (!empty($response)) {
    if (isset($response->result)) {

        $score = $response->result;

        if ((float)$score > (float)$banOnProbability) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['response'] = $response;

            if ($score < 0 || strcmp($score, "") == 0) {
                if ((int)$score === -5) {
                    $result['success'] = true;
                }
            }
        }
    }
}

?>