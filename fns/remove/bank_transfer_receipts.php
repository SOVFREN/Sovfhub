<?php
$result = array();
$noerror = true;

$result['success'] = false;
$result['error_message'] = Registry::load('strings')->went_wrong;
$result['error_key'] = 'something_went_wrong';
$bank_transfer_receipt_ids = array();

if (role(['permissions' => ['bank_transfer_receipts' => 'delete']])) {

    if (isset($data['bank_transfer_receipt_id'])) {
        if (!is_array($data['bank_transfer_receipt_id'])) {
            $data["bank_transfer_receipt_id"] = filter_var($data["bank_transfer_receipt_id"], FILTER_SANITIZE_NUMBER_INT);
            $bank_transfer_receipt_ids[] = $data["bank_transfer_receipt_id"];
        } else {
            $bank_transfer_receipt_ids = array_filter($data["bank_transfer_receipt_id"], 'ctype_digit');
        }
    }

    if (isset($data['bank_transfer_receipt_id']) && !empty($data['bank_transfer_receipt_id'])) {

        $receipt_files = DB::connect()->select("bank_transfer_receipts", ["receipt_file_name"], ["bank_transfer_receipt_id" => $bank_transfer_receipt_ids]);

        foreach ($receipt_files as $receipt_file) {
            $receipt_file_location = 'assets/files/bank_receipts/'.$receipt_file["receipt_file_name"];

            if (file_exists($receipt_file_location)) {
                unlink($receipt_file_location);
            }
        }

        DB::connect()->delete("bank_transfer_receipts", ["bank_transfer_receipt_id" => $bank_transfer_receipt_ids]);

        if (!DB::connect()->error) {
            $result = array();
            $result['success'] = true;
            $result['todo'] = 'reload';
            $result['reload'] = 'bank_transfer_receipts';
        } else {
            $result['errormsg'] = Registry::load('strings')->went_wrong;
        }
    }
}
?>