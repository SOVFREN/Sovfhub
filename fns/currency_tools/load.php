<?php
function currency_converter($amount, $sourceCurrency, $setCurrency = null) {

    if (empty($setCurrency)) {
        $setCurrency = 'USD';
    }

    $api_url = 'https://open.er-api.com/v6/latest/'.$setCurrency;
    $cache_file = 'fns/currency_tools/'.strtolower($setCurrency).'_rates_cache.json';

    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < 36000) {
        $data = json_decode(file_get_contents($cache_file), true);
    } else {
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response, true);

        if ($data && isset($data['rates'])) {
            file_put_contents($cache_file, json_encode($data));
        }
    }

    $exchangeRates = array();

    if (isset($data['rates'])) {
        $exchangeRates = $data['rates'];
    }
    if ($sourceCurrency == 'USD' && $setCurrency == 'USD') {
        $convertedAmount = $amount;
    } elseif (isset($exchangeRates[$sourceCurrency])) {
        $usdAmount = $amount / $exchangeRates[$sourceCurrency];
        $convertedAmount = $usdAmount;
    } else {
        $convertedAmount = null;
    }

    return $convertedAmount;
}