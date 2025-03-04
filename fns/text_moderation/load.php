<?php

function moderate_text_content($text) {

    $result = array();
    $result['success'] = true;

    $skip_text_moderation = role(['find' => 'skip_text_moderation']);

    if ($skip_text_moderation !== 'yes') {

        if (!empty(Registry::load('settings')->text_moderation) && Registry::load('settings')->text_moderation !== 'disable') {

            $text_moderation = Registry::load('settings')->text_moderation;

            if (isset($text) && !empty($text)) {

                if (isset($text_moderation) && !empty($text_moderation)) {
                    $load_fn_file = 'fns/text_moderation/'.$text_moderation.'.php';
                    if (file_exists($load_fn_file)) {
                        include($load_fn_file);
                    }
                }
            }

        }
    }

    return $result;
}