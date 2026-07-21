<?php

/*return [
    'mid' => env('PAYTM_MID'),
    'env' => env('PAYTM_ENV', 'staging'),
    'website' => env('PAYTM_WEBSITE', 'WEBSTAGING'),
    'callback_url' => env('PAYTM_CALLBACK_URL'),
    'secret_key' => env('PAYTM_SECRET_KEY'),
    'base_url' => env('PAYTM_ENV', 'staging') === 'production'
        ? 'https://secure.paytmpayments.com/theia/api/v1'
        : 'https://securestage.paytmpayments.com/theia/api/v1',
    'txn_url' => env('PAYTM_ENV', 'staging') === 'production'
        ? 'https://securegw.paytm.in/theia/processTransaction'
        : 'https://securegw-stage.paytm.in/theia/processTransaction',
];*/

return [
    'merchant_id'     => env('PAYTM_MERCHANT_ID', 'USkZxO98025496332498'),
    'merchant_key'    => env('PAYTM_MERCHANT_KEY', 'haSN&DAHGm%Bo6Yg'),
    'website'         => env('PAYTM_WEBSITE', 'WEBSTAGING'), // staging or configured
    'initiate_url'    => 'https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=%s&orderId=%s',
    'status_api_url'  => 'https://securegw-stage.paytm.in/v3/order/status',
    'callback_url'    => env('PAYTM_CALLBACK_URL', 'https://wisemudra.com/api/paytm/callback'),
    'js_sdk_url'      => 'https://securegw-stage.paytm.in/merchantpgpui/checkoutjs/merchants/%s.js',
    'env'             => env('PAYTM_ENVIRONMENT', 'staging'), // production or staging
];

