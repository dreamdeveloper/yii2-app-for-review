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
    'client_id'      => 'AZxwxf0fbbeO_BVnMMzFr8Y8xvHG7xnFk9tMZR9UOxYWffkLFc8tFDSdrSWJAyETXHuDOYc5pQh0WjH5',
    'secret'         => 'EJyPd6_FX3hRQBR9LF0VQAp_x7DZPkwdrK62QpIRImM2wgD30gXB2e0GXCzXy-8Uu8vbAwPlAH4yaQUt',
    'business_owner' => 'Leader95-facilitator@list.ru',
];

return \yii\helpers\ArrayHelper::merge(['config' => [
        'mode'                   => 'sandbox',
        'http.ConnectionTimeOut' => 60,
        'log.LogEnabled'         => false,
        'log.FileName'           => '@api/runtime/PayPal.log',
        'log.LogLevel'           => 'FINE',
    ]
], $setting);