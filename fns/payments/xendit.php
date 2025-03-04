<?php
include_once 'fns/payments/xendit/autoload.php';

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;


$secret_key = null;

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->xendit_api_key)) {
            $secret_key = $credentials->xendit_api_key;
        }

    }

}


if (empty($secret_key)) {
    echo "Invalid Xendit Credentials";
    exit;
}

if (isset($payment_data['purchase'])) {

    try {
        Configuration::setXenditKey($secret_key);

        $apiInstance = new InvoiceApi();

        $payment_currency = Registry::load('settings')->default_currency;

        $xendit_currencies = ['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR', 'SGD'];

        if (!in_array($payment_currency, $xendit_currencies)) {

            $payment_currency = 'USD';

            include_once "fns/currency_tools/load.php";
            $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency);

            if (empty($payment_data['purchase'])) {
                echo "Currency Conversion Failed";
                exit;
            }
        }

        $requestData = [
            'external_id' => $payment_data['transactionId'],
            'description' => $payment_data['package_name'],
            'amount' => $payment_data['purchase'],
            'currency' => $payment_currency,
            'success_redirect_url' => $payment_data['validation_url'],
            'failure_redirect_url' => $payment_data['validation_url'],
        ];
        $create_invoice_request = new CreateInvoiceRequest($requestData);
        $inv_result = $apiInstance->createInvoice($create_invoice_request);


        if (isset($inv_result['invoice_url'])) {

            $invoiceUrl = $inv_result['invoice_url'];

            $payment_session_data = array();
            $payment_session_data["payment_session_id"] = $inv_result['id'];
            $payment_session_data["payment_external_id"] = $inv_result['external_id'];
            $payment_session_data = json_encode($payment_session_data);

            DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);

            header('Location: ' . $invoiceUrl);
            exit();
        } else {
            redirect($payment_data['validation_url']);
        }
    } catch (\Xendit\XenditSdkException $e) {

        if (isset($debug_method) && $debug_method) {
            echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
            echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
            exit;
        }

        $error_log = $e->getFullError();

        if ($error_log->errorCode === 'UNSUPPORTED_CURRENCY') {
            echo $payment_currency." Currency Not Supported (Check Xendit Account)";
            exit;
        }


        redirect($payment_data['validation_url']);
    }
} else if (isset($payment_data['validate_purchase'])) {

    $transaction_info = array_merge($_GET, $_POST);
    $result = array();
    $result['success'] = false;
    $result['transaction_info'] = $transaction_info;
    $result['error'] = 'something_went_wrong';
    $session_id = null;


    if (isset($payment_data["payment_session_id"])) {
        $session_id = $payment_data["payment_session_id"];
        $transaction_info['payment_session_id'] = $session_id;
    }

    if (!empty($session_id)) {

        try {

            Configuration::setXenditKey($secret_key);
            $apiInstance = new InvoiceApi();
            $inv_result = $apiInstance->getInvoiceById($session_id);

            if ($inv_result['status'] === 'PAID') {
                $result = array();
                $result['success'] = true;
                $result['transaction_info'] = $transaction_info;
            } else {
                $result['error'] = $inv_result['status'];
            }

        } catch (\Xendit\XenditSdkException $e) {

            if (isset($debug_method) && $debug_method) {
                echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
                echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
                exit;
            }
            $result['error'] = $e->getMessage();
        }
    }
}