<?php

namespace Jetfuel\Jinyangpay;

use Jetfuel\Jinyangpay\Traits\ResultParser;

class DigitalPayment extends Payment
{
    use ResultParser;


    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param int $channel
     * @param float $amount
     * @param string $notifyUrl
     * @return array|null
     */
    public function order($tradeNo, $channel, $amount, $notifyUrl)
    {
        $payload = $this->signPayload([
            'p2_paytype'        => $channel,
            'p3_paymoney'       => $amount,
            'p4_orderno'        => $tradeNo,
            'p5_callbackurl'    => $notifyUrl,
            'p6_notifyurl'      => '',
            'p7_version'        => 'v2.8',
            'p8_signtype'       => 1,
            'p9_attach'         => '',
            'p10_appname'       => '',
            'p11_isshow'        => 0,
            'p12_orderip'       => '',
        ]);

        return $this->parseResponse($this->httpClient->post('zfapi/order/pay', $payload));

    }
}
