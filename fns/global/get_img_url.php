<?php

$result = null;
$replace_with_default = true;
$found = false;

if (isset($data['replace_with_default']) && !$data['replace_with_default']) {
    $replace_with_default = false;
}

$storage_public_url = Registry::load('config')->site_url;

if (Registry::load('settings')->cloud_storage !== 'disable') {
    if (!empty(Registry::load('settings')->cloud_storage_public_url)) {
        $storage_public_url = Registry::load('settings')->cloud_storage_public_url;
    }
}

if (isset($data['image']) && !empty($data['image'])) {
    $result = $storage_public_url.$data['image'];
    $found = true;
} else if ($replace_with_default) {
    $result = $storage_public_url.'assets/files/'.$data['from'].'/default.png';
}

if (!$found && Registry::load('settings')->gravatar === 'enable') {
    if (isset($data['gravatar']) && filter_var($data['gravatar'], FILTER_VALIDATE_EMAIL)) {
        $result = 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($data['gravatar'])))."?s=150&d=mp&r=g";
    }
}