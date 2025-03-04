<?php

$settings = extract_json(['file' => 'assets/cache/settings.cache']);

$contents = '';
if ($settings->push_notifications === 'onesignal') {
    $contents .= "importScripts('https://cdn.onesignal.com/sdks/OneSignalSDKWorker.js');\n";
} else if ($settings->push_notifications === 'webpushr') {
    $contents .= "importScripts('https://cdn.webpushr.com/sw-server.min.js');\n";
}

if ($settings->progressive_web_application === 'enable') {
    $contents .= "importScripts('".Registry::load('config')->site_url."pwa-sw.js');";
}

$sw_file = 'service_worker.js';
if (file_exists($sw_file)) {
    unlink($sw_file);
}

$cachefile = fopen($sw_file, "w");
fwrite($cachefile, $contents);
fclose($cachefile);

?>