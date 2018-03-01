<?php

namespace Jetfuel\Jinyangpay;

use Jetfuel\Jinyangpay\Traits\ResultParser;

class TradeQuery extends Payment
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
     * Find Order by trade number.
     *
     * @param string $tradeNo
     * @return array|null
     */
    public function find($tradeNo)
    {
        $payload = $this->signQueryPayload([
            'p3_orderno' => $tradeNo,
        ]);

        $order = $this->parseResponse($this->httpClient->post('zfapi/order/singlequery', $payload));

        if ($order === null || !isset($order['rspCode']) || $order['rspCode'] !== 1) {
            return null;
        }

        return $order;
    }

    /**
     * Is order already paid.
     *
     * @param string $tradeNo
     * @return bool
     */
    public function isPaid($tradeNo)
    {
        $order = $this->find($tradeNo);

        if ($order === null || !isset($order['data']['r5_orderstate']) || $order['data']['r5_orderstate'] !== 1) {
            return false;
        }

        return true;
    }
}
