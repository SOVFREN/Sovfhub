<?php

$file_upload_failed = false;

if (isset($_FILES['bank_receipt']['name']) && !empty($_FILES['bank_receipt']['name'])) {

    include 'fns/filters/load.php';
    include 'fns/files/load.php';

    $extension = pathinfo($_FILES['bank_receipt']['name'])['extension'];
    $filename = $order_id.Registry::load('config')->file_seperator.random_string(['length' => 6]).'.'.$extension;

    if (isset($bank_receipt[0])) {
        $prev_receipt_location = 'assets/files/bank_receipts/'.$bank_receipt[0]['receipt_file_name'];

        if (file_exists($prev_receipt_location)) {
            unlink($prev_receipt_location);
        }
    }

    if (files('upload', ['upload' => 'bank_receipt', 'folder' => 'bank_receipts', 'saveas' => $filename])['result']) {
        $receipt_location = 'assets/files/bank_receipts/'.$filename;

        if (file_exists($receipt_location)) {
            $mime_type = mime_content_type($receipt_location);

            $valid_mime_types = [
                'application/pdf',
                'application/x-pdf',
                'application/acrobat',
                'application/vnd.pdf',
                'image/jpeg',
                'image/jpg',
                'image/png',
            ];

            if (!in_array($mime_type, $valid_mime_types)) {
                unlink($receipt_location);
            } else {

                if (isset($bank_receipt[0])) {
                    DB::connect()->update('bank_transfer_receipts',
                        ['receipt_file_name' => $filename, 'updated_on' => Registry::load('current_user')->time_stamp],
                        ['bank_transfer_receipt_id' => $bank_receipt[0]['bank_transfer_receipt_id']]
                    );
                } else {

                    $insert_data = [
                        'membership_order_id' => $order_id,
                        'receipt_file_name' => $filename,
                        'created_on' => Registry::load('current_user')->time_stamp,
                        'updated_on' => Registry::load('current_user')->time_stamp
                    ];
                    DB::connect()->insert('bank_transfer_receipts', $insert_data);
                }
            }
        }

        header("Refresh:0");
    } else {
        $file_upload_failed = true;
    }
}
?>