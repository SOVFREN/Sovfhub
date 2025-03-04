<?php

if (!empty(Registry::load('settings')->perspective_google_cloud_api_key)) {

    $skip_text = false;
    $reason_for_removal = '';

    $apiEndpoint = 'https://commentanalyzer.googleapis.com/v1alpha1/comments:analyze';

    $requestData = [
        'comment' => ['text' => $text],
        'requestedAttributes' => [
            'TOXICITY' => new \stdClass(),
            'PROFANITY' => new \stdClass(),
            'THREAT' => new \stdClass(),
            'SEVERE_TOXICITY' => new \stdClass(),
            'IDENTITY_ATTACK' => new \stdClass(),
            'INSULT' => new \stdClass(),
        ],
        'doNotStore' => true
    ];


    $jsonData = json_encode($requestData);
    $google_cloud_api_key = Registry::load('settings')->perspective_google_cloud_api_key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiEndpoint . '?key=' . $google_cloud_api_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
    ]);

    $curl_response = curl_exec($ch);
    curl_close($ch);

    $curl_response = json_decode($curl_response, true);

    $perspective_api_threshold = 0.9;

    if (!empty(Registry::load('settings')->perspective_api_threshold)) {
        $perspective_api_threshold = (float)Registry::load('settings')->perspective_api_threshold;
    }

    if (!empty($curl_response) && isset($curl_response['attributeScores'])) {
        if (isset($curl_response['attributeScores'])) {

            if (isset(Registry::load('settings')->perspectiveapi_text_removal_criteria->threat)) {
                $content_score = $curl_response['attributeScores']['THREAT']['summaryScore']['value'];

                if ((float)$content_score >= $perspective_api_threshold) {
                    $reason_for_removal = 'THREAT';
                    $skip_text = true;
                }
            }

            if (isset(Registry::load('settings')->perspectiveapi_text_removal_criteria->severe_toxicity)) {
                $content_score = $curl_response['attributeScores']['SEVERE_TOXICITY']['summaryScore']['value'];

                if ((float)$content_score >= $perspective_api_threshold) {
                    $reason_for_removal = 'SEVERE TOXICITY';
                    $skip_text = true;
                }
            }

            if (isset(Registry::load('settings')->perspectiveapi_text_removal_criteria->toxicity)) {
                $content_score = $curl_response['attributeScores']['TOXICITY']['summaryScore']['value'];

                if ((float)$content_score >= $perspective_api_threshold) {
                    $reason_for_removal = 'TOXICITY';
                    $skip_text = true;
                }
            }


            if (isset(Registry::load('settings')->perspectiveapi_text_removal_criteria->profanity)) {
                $content_score = $curl_response['attributeScores']['PROFANITY']['summaryScore']['value'];

                if ((float)$content_score >= $perspective_api_threshold) {
                    $reason_for_removal = 'PROFANITY';
                    $skip_text = true;
                }
            }


            if (isset(Registry::load('settings')->perspectiveapi_text_removal_criteria->identity_attack)) {
                $content_score = $curl_response['attributeScores']['IDENTITY_ATTACK']['summaryScore']['value'];

                if ((float)$content_score >= $perspective_api_threshold) {
                    $reason_for_removal = 'IDENTITY ATTACK';
                    $skip_text = true;
                }
            }


            if (isset(Registry::load('settings')->perspectiveapi_text_removal_criteria->insult)) {
                $content_score = $curl_response['attributeScores']['INSULT']['summaryScore']['value'];

                if ((float)$content_score >= $perspective_api_threshold) {
                    $reason_for_removal = 'INSULT';
                    $skip_text = true;
                }
            }

            if ($skip_text) {
                $result = array();
                $result['success'] = false;
                $result['reason'] = $reason_for_removal;
            }
        }
    }
}