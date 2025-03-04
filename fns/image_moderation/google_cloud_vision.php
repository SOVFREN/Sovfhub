<?php

if (!empty(Registry::load('settings')->google_cloud_vision_api_key)) {

    $image_location = $image;
    $skip_image = false;
    $reason_for_removal = '';

    $apiEndpoint = 'https://vision.googleapis.com/v1/images:annotate';
    $imageData = base64_encode(file_get_contents($image_location));

    $requestData = [
        'requests' => [
            [
                'image' => ['content' => $imageData],
                'features' => [['type' => 'SAFE_SEARCH_DETECTION']],
            ],
        ],
    ];

    $jsonData = json_encode($requestData);
    $google_cloud_vision_api_key = Registry::load('settings')->google_cloud_vision_api_key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiEndpoint . '?key=' . $google_cloud_vision_api_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData),
    ]);

    $cloud_vision_response = curl_exec($ch);
    curl_close($ch);

    $cloud_vision_response = json_decode($cloud_vision_response, true);

    $skip_values = ['LIKELY', 'VERY_LIKELY', 'POSSIBLE'];
    
    if (!empty($cloud_vision_response) && isset($cloud_vision_response['responses'])) {
        if (isset($cloud_vision_response['responses'][0]['safeSearchAnnotation'])) {

            $gv_result = $cloud_vision_response['responses'][0]['safeSearchAnnotation'];

            if (isset(Registry::load('settings')->gcv_image_removal_criteria->adult_content)) {
                if (in_array($gv_result['adult'], $skip_values)) {
                    $reason_for_removal = 'adult_content';
                    $skip_image = true;
                }
            }

            if (isset(Registry::load('settings')->gcv_image_removal_criteria->spoof_content)) {
                if (in_array($gv_result['spoof'], $skip_values)) {
                    $reason_for_removal = 'spoof_content';
                    $skip_image = true;
                }
            }

            if (isset(Registry::load('settings')->gcv_image_removal_criteria->medical_content)) {
                if (in_array($gv_result['medical'], $skip_values)) {
                    $reason_for_removal = 'medical_content';
                    $skip_image = true;
                }
            }

            if (isset(Registry::load('settings')->gcv_image_removal_criteria->violence_content)) {
                if (in_array($gv_result['violence'], $skip_values)) {
                    $reason_for_removal = 'violence_content';
                    $skip_image = true;
                }
            }

            if (isset(Registry::load('settings')->gcv_image_removal_criteria->racy_content)) {
                if (in_array($gv_result['racy'], $skip_values)) {
                    $reason_for_removal = 'racy_content';
                    $skip_image = true;
                }
            }

            if ($skip_image) {
                $result = array();
                $result['success'] = false;
                $result['reason'] = $reason_for_removal;
            }
        }
    }
}