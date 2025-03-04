<?php

$config = new stdClass();

$config->app_name='Grupo Chat';
$config->app_version='3.7';

$config->site_url="http://localhost/grupo/";
$config->force_url=false;
$config->force_https=false;
$config->developer_mode = false;
$config->csrf_token=true;

$config->samesite_cookies='None';
$config->http_only_cookies=false;
$config->cookie_domain="";
$config->timezone='Asia/Kolkata';
$config->file_seperator='-gr-';

$config->pro_version = 'pro';
$db_error_mode=PDO::ERRMODE_SILENT;

if ($config->developer_mode) {
    $db_error_mode=PDO::ERRMODE_EXCEPTION;
}

$config->database=[
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'bacefbae_chatsovbase',
    'username' => 'bacefbae_dev',
    'password' => 'Ax51Guj1988=',
    'port' => '3306',
    'prefix' => 'gr_',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'logging' => false,
    'error' => $db_error_mode,
];
