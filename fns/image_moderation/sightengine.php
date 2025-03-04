<?php

if (!empty(Registry::load('settings')->sightengine_api_user) && !empty(Registry::load('settings')->sightengine_api_secret)) {

    $image_location = $image;
    $skip_image = false;
    $maxmimum_score = 70;
    $reason_for_removal = '';
    $image_moderation_params = array(
        'media' => new CurlFile($image_location),
        'models' => 'nudity,wad,offensive,gore',
        'api_user' => Registry::load('settings')->sightengine_api_user,
        'api_secret' => Registry::load('settings')->sightengine_api_secret
    );

    $sightengine_request = curl_init('https://api.sightengine.com/1.0/check.json');
    curl_setopt($sightengine_request, CURLOPT_POST, true);
    curl_setopt($sightengine_request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($sightengine_request, CURLOPT_POSTFIELDS, $image_moderation_params);
    $sightengine_response = curl_exec($sightengine_request);
    curl_close($sightengine_request);

    $sightengine_response = json_decode($sightengine_response, true);

    if (!empty($sightengine_response) && $sightengine_response['status'] === 'success') {

        if (isset(Registry::load('settings')->image_removal_criteria->partial_nudity)) {

            $maxmimum_score = Registry::load('settings')->minimum_score_required_partial_nudity;
            if (empty($maxmimum_score) || (int)$maxmimum_score > 100) {
                $maxmimum_score = 70;
            } else {
                $maxmimum_score = 100 - (int)$maxmimum_score;
            }

            if (isset($sightengine_response['nudity']['partial'])) {
                $content_score = $sightengine_response['nudity']['partial'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'partial_nudity';
                    $skip_image = true;
                }
            }
        }

        if (isset(Registry::load('settings')->image_removal_criteria->explicit_nudity)) {

            $maxmimum_score = Registry::load('settings')->minimum_score_required_explicit_nudity;
            if (empty($maxmimum_score) || (int)$maxmimum_score > 100) {
                $maxmimum_score = 70;
            } else {
                $maxmimum_score = 100 - (int)$maxmimum_score;
            }

            if (isset($sightengine_response['nudity']['raw'])) {
                $content_score = $sightengine_response['nudity']['raw'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'explicit_nudity';
                    $skip_image = true;
                }
            }
        }

        if (isset(Registry::load('settings')->image_removal_criteria->offensive_signs_gestures)) {

            $maxmimum_score = Registry::load('settings')->minimum_score_required_offensive;
            if (empty($maxmimum_score) || (int)$maxmimum_score > 100) {
                $maxmimum_score = 70;
            } else {
                $maxmimum_score = 100 - (int)$maxmimum_score;
            }

            if (isset($sightengine_response['offensive']['prob'])) {
                $content_score = $sightengine_response['offensive']['prob'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'offensive';
                    $skip_image = true;
                }
            }
        }

        if (isset(Registry::load('settings')->image_removal_criteria->graphic_violence_gore)) {

            $maxmimum_score = Registry::load('settings')->minimum_score_required_graphic_violence_gore;
            if (empty($maxmimum_score) || (int)$maxmimum_score > 100) {
                $maxmimum_score = 70;
            } else {
                $maxmimum_score = 100 - (int)$maxmimum_score;
            }

            if (isset($sightengine_response['gore']['prob'])) {
                $content_score = $sightengine_response['gore']['prob'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'gore';
                    $skip_image = true;
                }
            }
        }

        if (isset(Registry::load('settings')->image_removal_criteria->wad_content)) {

            $maxmimum_score = Registry::load('settings')->minimum_score_required_wad_content;
            if (empty($maxmimum_score) || (int)$maxmimum_score > 100) {
                $maxmimum_score = 70;
            } else {
                $maxmimum_score = 100 - (int)$maxmimum_score;
            }

            if (isset($sightengine_response['weapon'])) {
                $content_score = $sightengine_response['weapon'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'weapon';
                    $skip_image = true;
                }
            }

            if (isset($sightengine_response['alcohol'])) {
                $content_score = $sightengine_response['alcohol'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'alcohol';
                    $skip_image = true;
                }
            }

            if (isset($sightengine_response['drugs'])) {
                $content_score = $sightengine_response['drugs'];
                $content_score = (float)$content_score*100;

                if ((int)$content_score > (int)$maxmimum_score) {
                    $reason_for_removal = 'drugs';
                    $skip_image = true;
                }
            }
        }

        if ($skip_image) {
            $result = array();
            $result['success'] = false;
            $result['reason'] = $reason_for_removal;
        }

    }
}