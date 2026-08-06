<?php

return [
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

    'SELF_INTERAKT_KEY'     => env('SELF_INTERAKT_KEY'),
    'HIRE_INTERAKT_KEY'     => env('HIRE_INTERAKT_KEY'),

    'REMARKETING_MOBILE_NUMBERS' => array_map('trim',explode(',', env('REMARKETING_MOBILE_NUMBERS', ''))),
];
