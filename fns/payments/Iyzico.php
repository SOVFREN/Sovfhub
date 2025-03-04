<?php
include_once 'fns/payments/iyzico/IyzipayBootstrap.php';

$api_key = null;
$secret_key = null;
$iyzipay_base_url = 'https://sandbox-api.iyzipay.com';

if (isset($payment_data['credentials']) && !empty($payment_data['credentials'])) {

    $credentials = json_decode($payment_data['credentials']);

    if (!empty($credentials)) {
        if (isset($credentials->iyzico_api_key)) {
            $api_key = $credentials->iyzico_api_key;
        }

        if (isset($credentials->iyzico_secret_key)) {
            $secret_key = $credentials->iyzico_secret_key;
        }

        if (isset($credentials->iyzico_sandbox) && $credentials->iyzico_sandbox !== 'yes') {
            $iyzipay_base_url = 'https://api.iyzipay.com';
        }

    }

}


if (empty($api_key) || empty($secret_key)) {
    echo "Invalid iyzico Credentials";
    exit;
}


IyzipayBootstrap::init();
$iyzico_config = new \Iyzipay\Options();
$iyzico_config->setApiKey($api_key);
$iyzico_config->setSecretKey($secret_key);
$iyzico_config->setBaseUrl($iyzipay_base_url);

if (isset($payment_data['purchase'])) {

    try {

        $iyzipay_request = new \Iyzipay\Request\CreatePayWithIyzicoInitializeRequest();
        $iyzipay_request->setLocale(\Iyzipay\Model\Locale::EN);
        $iyzipay_request->setConversationId($payment_data['transactionId']);

        Registry::load('settings')->default_currency = 'INR';

        if (Registry::load('settings')->default_currency === 'TRY') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::TL);
        } else if (Registry::load('settings')->default_currency === 'USD') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::USD);
        } else if (Registry::load('settings')->default_currency === 'EUR') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::EUR);
        } else if (Registry::load('settings')->default_currency === 'GBP') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::GBP);
        } else if (Registry::load('settings')->default_currency === 'RUB') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::RUB);
        } else if (Registry::load('settings')->default_currency === 'NOK') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::NOK);
        } else if (Registry::load('settings')->default_currency === 'CHF') {
            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::CHF);
        } else {
            include_once "fns/currency_tools/load.php";
            $payment_data['purchase'] = currency_converter($payment_data['purchase'], Registry::load('settings')->default_currency);

            if (empty($payment_data['purchase'])) {
                echo "Currency Conversion Failed";
                exit;
            }

            $iyzipay_request->setCurrency(\Iyzipay\Model\Currency::USD);
        }

        $iyzipay_request->setPrice($payment_data['purchase']);
        $iyzipay_request->setPaidPrice($payment_data['purchase']);
        $iyzipay_request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $iyzipay_request->setCallbackUrl($payment_data['validation_url']);

        $columns = $join = $where = null;
        $columns = ['billed_to', 'street_address', 'city', 'state', 'country', 'postal_code'];
        $where["billing_address.user_id"] = Registry::load('current_user')->id;
        $billing_address = DB::connect()->select('billing_address', $columns, $where);

        if (!empty($billing_address)) {
            $billing_address = $billing_address[0];
        } else {
            $billing_address = array();
            $billing_address['billed_to'] = Registry::load('current_user')->name;
            $billing_address['street_address'] = $billing_address['city'] = 'Unknown';
            $billing_address['state'] = $billing_address['country'] = 'Unknown';
            $billing_address['postal_code'] = '123456';
        }

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId(Registry::load('current_user')->id);
        $buyer->setName(Registry::load('current_user')->name);
        $buyer->setSurname(Registry::load('current_user')->username);
        $buyer->setEmail(Registry::load('current_user')->email_address);
        $buyer->setIdentityNumber("0123456789");
        $buyer->setRegistrationAddress($billing_address['street_address']);
        $buyer->setCity($billing_address['city']);
        $buyer->setCountry($billing_address['country']);
        $buyer->setZipCode($billing_address['postal_code']);
        $iyzipay_request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName($billing_address['billed_to']);
        $shippingAddress->setCity($billing_address['city']);
        $shippingAddress->setCountry($billing_address['country']);
        $shippingAddress->setAddress($billing_address['street_address']);
        $shippingAddress->setZipCode($billing_address['postal_code']);
        $iyzipay_request->setShippingAddress($shippingAddress);


        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName($billing_address['billed_to']);
        $billingAddress->setCity($billing_address['city']);
        $billingAddress->setCountry($billing_address['country']);
        $billingAddress->setAddress($billing_address['street_address']);
        $billingAddress->setZipCode($billing_address['postal_code']);
        $iyzipay_request->setBillingAddress($billingAddress);


        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId($payment_data['transactionId']);
        $firstBasketItem->setName($payment_data['package_name']);
        $firstBasketItem->setCategory1("Membership Plan");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($payment_data['purchase']);
        $basketItems[0] = $firstBasketItem;
        $iyzipay_request->setBasketItems($basketItems);

        $payWithIyzicoInitialize = \Iyzipay\Model\PayWithIyzicoInitialize::create($iyzipay_request, $iyzico_config);

        $rawResult = $payWithIyzicoInitialize->getRawResult();
        $rawResult = json_decode($rawResult);

        if (!empty($rawResult) && isset($rawResult->status) && ($rawResult->status === 'success')) {
            $payment_session_data = array();
            $payment_session_data["payment_conversationId"] = $rawResult->conversationId;
            $payment_session_data["payment_token"] = $rawResult->token;

            $payment_session_data = json_encode($payment_session_data);
            DB::connect()->update('membership_orders', ['transaction_info' => $payment_session_data], ['order_id' => $payment_data['transactionId']]);


            header('Location: ' . $rawResult->payWithIyzicoPageUrl);
            exit();
        } else {
            redirect($payment_data['validation_url']);
        }
    } catch (Exception $e) {
        redirect($payment_data['validation_url']);
    }
} else if (isset($payment_data['validate_purchase'])) {

    $transaction_info = array_merge($_GET, $_POST);

    $result = array();
    $result['success'] = false;
    $result['transaction_info'] = $transaction_info;
    $result['error'] = 'something_went_wrong';
    $conversationId = null;


    if (isset($payment_data["validate_purchase"])) {
        $conversationId = $payment_data["validate_purchase"];
    }

    if (!empty($conversationId)) {
        try {

            $iyzipay_request = new \Iyzipay\Request\RetrievePaymentRequest();
            $iyzipay_request->setLocale(\Iyzipay\Model\Locale::EN);
            $iyzipay_request->setConversationId($conversationId);
            $iyzipay_request->setPaymentConversationId($conversationId);

            $payment = \Iyzipay\Model\Payment::retrieve($iyzipay_request, $iyzico_config);

            $rawResult = $payment->getRawResult();
            $rawResult = json_decode($rawResult);

            if (!empty($rawResult) && isset($rawResult->status) && ($rawResult->status === 'success')) {

                $transaction_info['paymentId'] = $rawResult->paymentId;
                $transaction_info['paidPrice'] = $rawResult->paidPrice;

                $result = array();
                $result['success'] = true;
                $result['transaction_info'] = $transaction_info;
            } else {
                $result['error'] = $rawResult->errorMessage;
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }
    }
}