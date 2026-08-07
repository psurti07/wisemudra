<?php

return [
    'APP_NAME'          => env('APP_NAME'),

    'COMPANY_CODE'          => 'WSMDRA123',
    'PRODUCT_CODE_SELFAPPLY'  => 'SELFAPPLY',
    'PRODUCT_CODE_LOANAGENT'  => 'HIRELOAN',
    'COMPANY_NAME'          => env('COMPANY_NAME'),
    'COMPANY_ADDRESS'       => env('COMPANY_ADDRESS'),
    'COMPANY_MOBILE'       => env('COMPANY_MOBILE'),
    'COMPANY_INFO_MAIL'     => env('COMPANY_INFO_MAIL'),
    'COMPANY_SUPPORT_MAIL'  => env('COMPANY_SUPPORT_MAIL'),

    'SM_TWITTER'            => env('SM_TWITTER'),
    'SM_PINTEREST'          => env('SM_PINTEREST'),
    'SM_YOUTUBE'            => env('SM_YOUTUBE'),
    'SM_INSTAGRAM'          => env('SM_INSTAGRAM'),
    'SM_FACEBOOK'           => env('SM_FACEBOOK'),
    'SM_LINKEDIN'           => env('SM_LINKEDIN'),

    'INFO_EMAIL'            => env('INFO_EMAIL'),

    'CIN_NO'                => env('CIN_NO'),
    'GST_NO'                => env('GST_NO'),

    // LA Offers
    'LA_OFFER_1'            => env('LA_OFFER_1'),
    'LA_OFFER_2'            => env('LA_OFFER_2'),
    'LA_OFFER_3'            => env('LA_OFFER_3'),
    'LA_OFFER_4'            => env('LA_OFFER_4'),
    'LA_OFFER_5'            => env('LA_OFFER_5'),
    'LA_OFFER_6'            => env('LA_OFFER_6'),

    // SA Offers
    'SA_OFFER_1'            => env('SA_OFFER_1'),
    'SA_OFFER_2'            => env('SA_OFFER_2'),
    'SA_OFFER_3'            => env('SA_OFFER_3'),
    'SA_OFFER_4'            => env('SA_OFFER_4'),
    'SA_OFFER_5'            => env('SA_OFFER_5'),
    'SA_OFFER_6'            => env('SA_OFFER_6'),
    'SA_OFFER_7'            => env('SA_OFFER_7'),


    'SMS_OBB_USERNAME'      => env('SMS_OBB_USERNAME'),
    'SMS_OBB_PASSWORD'      => env('SMS_OBB_PASSWORD'),
    'SMS_OBB_SENDER_ID'     => env('SMS_OBB_SENDER_ID'),

    // LA SMS OBB
    'SMS_OBB_LA_USERNAME'   => env('SMS_OBB_LA_USERNAME'),
    'SMS_OBB_LA_PASSWORD'   => env('SMS_OBB_LA_PASSWORD'),
    'SMS_OBB_LA_SENDER_ID'  => env('SMS_OBB_LA_SENDER_ID'),

    'BREVO_API_KEY'         => env('BREVO_API_KEY'),

    'GEOLOC_KEY'            => env('GEOLOC_KEY'),

    'RAZOR_KEY_ID'          => env('RAZOR_KEY_ID'),
    'RAZOR_KEY_SECRET'      => env('RAZOR_KEY_SECRET'),

    'PHONEPE_MERCHANT_ID'      => env('PHONEPE_MERCHANT_ID'),
    'PHONEPE_MERCHANT_USER_ID' => env('PHONEPE_MERCHANT_USER_ID'),
    'PHONEPE_ENV'              => env('PHONEPE_ENV'),
    'PHONEPE_SALT_KEY'         => env('PHONEPE_SALT_KEY'),
    'PHONEPE_SALT_INDEX'       => env('PHONEPE_SALT_INDEX'),

    // Airpay
    'AIRPAY_MERCHENT_ID' => env('AIRPAY_MERCHENT_ID'),
    'AIRPAY_USERNAME'    => env('AIRPAY_USERNAME'),
    'AIRPAY_PASSWORD'    => env('AIRPAY_PASSWORD'),
    'AIRPAY_API_KEY'     => env('AIRPAY_API_KEY'),

    // Lyra
    'LYRA_MODE'    => env('LYRA_MODE'),
    'LYRA_SHOP_ID' => env('LYRA_SHOP_ID'),
    'LYRA_LCID'    => env('LYRA_LCID'),
    'LYRA_MCC'     => env('LYRA_MCC'),
    'LYRA_API_KEY' => env('LYRA_API_KEY'),

    // SabPaisa
    'SABPAISA_MODE'      => env('SABPAISA_MODE'),
    'SABPAISA_CLIENT_CODE' => env('SABPAISA_CLIENT_CODE'),
    'SABPAISA_USERNAME'  => env('SABPAISA_USERNAME'),
    'SABPAISA_PASSWORD'  => env('SABPAISA_PASSWORD'),
    'SABPAISA_AUTH_KEY'  => env('SABPAISA_AUTH_KEY'),
    'SABPAISA_AUTH_IV'   => env('SABPAISA_AUTH_IV'),

    // Zaakpay
    'ZAAKPAY_ENV'                 => env('ZAAKPAY_ENV'),
    'ZAAKPAY_MERCHANT_IDENTIFIER' => env('ZAAKPAY_MERCHANT_IDENTIFIER'),
    'ZAAKPAY_SECRET_KEY'          => env('ZAAKPAY_SECRET_KEY'),
    'ZAAKPAY_API_KEY'             => env('ZAAKPAY_API_KEY'),

    // Cashfree
    'CASHFREE_APP_ID'     => env('CASHFREE_APP_ID'),
    'CASHFREE_SECRET_KEY' => env('CASHFREE_SECRET_KEY'),
    'CASHFREE_MODE'       => env('CASHFREE_MODE'),

    // Paygic
    'PAYGIC_PAYMENT_MODE' => env('PAYGIC_PAYMENT_MODE'),
    'PAYGIC_MERCHANT_ID'  => env('PAYGIC_MERCHANT_ID'),
    'PAYGIC_PASSWORD'     => env('PAYGIC_PASSWORD'),

    'SELF_INTERAKT_KEY'     => env('SELF_INTERAKT_KEY'),
    'HIRE_INTERAKT_KEY'     => env('HIRE_INTERAKT_KEY'),

    'WEBINAR_INTERAKT_KEY'     => env('WEBINAR_INTERAKT_KEY'),

    'UAT_MOBILE_NUMBERS' => array_map('trim',explode(',', env('UAT_MOBILE_NUMBERS', ''))),

    'REMARKETING_MOBILE_NUMBERS' => array_map('trim',explode(',', env('REMARKETING_MOBILE_NUMBERS', ''))),
];
