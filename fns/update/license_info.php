<?php

$result = array();
$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';

if (role(['permissions' => ['super_privileges' => 'core_settings']])) {
    $noerror = true;

    $result['success'] = false;
    $result['error_message'] = Registry::load('strings')->invalid_value;
    $result['error_key'] = 'invalid_value';
    $result['error_variables'] = [];

    $purchase_code = $data['purchase_code'];
    $result['error_message'] = 'Invalid Purchase Code';
    $result['error_key'] = 'invalid_purchase_code';

    $variables = new stdClass;
    $variables->license = 'VBASE90';
    $variables->extended_license = 'PRO';
    $variables->sold_at = '2024-07-01';
    $variables->supported_until = '9999-12-31';
    $variables->buyer = 'VBASE90 - BABIA.TO';

    if (!empty($variables)) {
        if (isset($variables->license)) {
            $license_info_file = 'assets/cache/license_record.cache';
            file_put_contents($license_info_file, $response);

            $configFile = 'include/config.php';
            $currentConfig = file_get_contents($configFile);
            copy($configFile, 'include/config_backup_copy.php');

            if (isset($variables->extended_license)) {
                $newLine = "\n\$config->pro_version = 'pro';\n";
                $position = strpos($currentConfig, '$db_error_mode=PDO::ERRMODE_SILENT;');

                if ($position !== false) {
                    $newConfig = substr_replace($currentConfig, $newLine, $position, 0);
                    file_put_contents($configFile, $newConfig);
                }
            } else {
                $lineToRemove = "\n\$config->pro_version = 'pro';";
                $newConfig = str_replace($lineToRemove, '', $currentConfig);
                file_put_contents($configFile, $newConfig);
            }

            $result = array();
            $result['success'] = true;
            $result['todo'] = 'refresh';
        }
    }
}

?>