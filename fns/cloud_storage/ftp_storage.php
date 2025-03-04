<?php

$result = array();
$result['success'] = false;
$result['error_key'] = 'something_went_wrong';

include_once('fns/cloud_storage/libraries/php_ftp/autoload.php');

$ftp_info = [
    'ftp_host' => '',
    'ftp_username' => '',
    'ftp_password' => '',
    'ftp_port' => '',
    'ftp_path' => '',
    'ssl' => false,
];

$ftp_login = false;

try {
    $ftp = new \FtpClient\FtpClient();
    $ftp->connect($ftp_info['ftp_host'], $ftp_info['ssl'], $ftp_info['ftp_port']);
    $ftp->login($ftp_info['ftp_username'], $ftp_info['ftp_password']);
    $ftp_login = true;
} catch (Exception $e) {
    $result['error_key'] = 'invalid_login';
}

if ($ftp_login) {
    if (!empty($ftp_info['ftp_path'])) {
        if ($ftp_info['ftp_path'] != "./") {
            $ftp->chdir($ftp_info['ftp_path']);
        }
    }

    if (isset($data['upload_file']) && !empty($data['upload_file'])) {
        if (file_exists($data['upload_file'])) {
            $file_path = substr($data['upload_file'], 0, strrpos($data['upload_file'], '/'));
            $file_path_info = explode('/', $file_path);
            $path = '';
            if (!$ftp->isDir($file_path)) {
                foreach ($file_path_info as $key => $value) {
                    if (!empty($path)) {
                        $path .= '/' . $value . '/';
                    } else {
                        $path .= $value . '/';
                    }
                    if (!$ftp->isDir($path)) {
                        $mkdir = $ftp->mkdir($path);
                    }
                }
            }
            $ftp->chdir($file_path);
            $ftp->pasv(true);

            $upload_success = false;
            try {
                $ftp->putFromPath($data['upload_file']);
                $upload_success = true;
            } catch (Exception $e) {
                $upload_success = false;
            }
            if ($upload_success) {

                if (isset($data['delete']) && $data['delete']) {
                    @unlink($data['upload_file']);
                }

                $ftp->close();

                $result = array();
                $result['success'] = true;
            } else {
                $result['error_key'] = 'upload_failed';
            }

            $ftp->close();
        } else {
            $result['error_key'] = 'file_not_found';
        }
    } else if (isset($data['delete_file']) && !empty($data['delete_file'])) {
        $file_path = substr($data['delete_file'], 0, strrpos($data['delete_file'], '/'));
        $file_name = substr($data['delete_file'], strrpos($data['delete_file'], '/') + 1);
        $file_path_info = explode('/', $data['delete_file']);
        $path = '';
        if (!$ftp->isDir($file_path)) {
            $result['error_key'] = 'file_not_found';
        } else {
            $ftp->chdir($file_path);
            $ftp->pasv(true);
            if ($ftp->remove($file_name)) {
                $result = array();
                $result['success'] = true;
            } else {
                $result['error_key'] = 'file_not_found';
            }
        }
    }
}


?>