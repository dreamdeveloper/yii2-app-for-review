<?php
/**
 * Information PAYPAL's enviroments
 * @var string
 */

// E.g:
// If enviroment is Development you should use mode = sandbox and endpoint = api.sandbox.paypal.com
// $setting = [
//     'endpoint'       => 'api.sandbox.paypal.com',
//     'client_id'      => 'AV92BhCOYzF4Vejrbphu1ksMn4KYSlvbzCTcbLdOMixBvAS7sQZhOvMNkMoG',
//     'secret'         => 'EDdjYm7i8w2XZwWGyTqPfPDJim2dUV1hX_3dhY0fR-HulrENli6043rY_0GO1ro1gnkxVe3bMWNDikvq',
//     'business_owner' => 'nguyentruongthanh.dn-facilitator-1@gmail.com',
// ];

// E.g:
// If enviroment is live you should use mode = live and endpoint = api.paypal.com
// $setting = [
//     'endpoint'       => 'api.paypal.com',
//     'client_id'      => 'AV92BhCOYzF4Vejrbphu1ksMn4KYSlvbzCTcbLdOMixBvAS7sQZhOvMNkMoG',
//     'secret'         => 'EDdjYm7i8w2XZwWGyTqPfPDJim2dUV1hX_3dhY0fR-HulrENli6043rY_0GO1ro1gnkxVe3bMWNDikvq',
//     'business_owner' => 'nguyentruongthanh.dn-facilitator-1@gmail.com',
// ];

$setting = [
    'endpoint'       => 'api.sandbox.paypal.com',
    'client_id'      => '',
    'secret'         => '',
    'business_owner' => '',
];

return \yii\helpers\ArrayHelper::merge(['config' => [
        'mode'                   => 'sandbox',
        'http.ConnectionTimeOut' => 60,
        'log.LogEnabled'         => false,
        'log.FileName'           => '@api/runtime/PayPal.log',
        'log.LogLevel'           => 'FINE',
    ]
], $setting);
