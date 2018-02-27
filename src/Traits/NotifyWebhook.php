<?php

namespace Jetfuel\Jinyangpay\Traits;

use Jetfuel\Jinyangpay\Signature;

trait NotifyWebhook
{
    /**
     * Verify notify request's signature.
     *
     * @param array $payload
     * @param $secretKey
     * @return bool
     */
    public function verifyNotifyPayload(array $payload, $secretKey)
    {
        if (!isset($payload['sign'])) {
            return false;
        }

        $signature = $payload['sign'];

        unset($payload['sysnumber']);
        unset($payload['attach']);
        unset($payload['sign']);

        return Signature::validateNotify($payload, $secretKey, $signature);
    }

    /**
     * Verify notify request's signature and parse payload.
     *
     * @param array $payload
     * @param string $secretKey
     * @return array|null
     */
    public function parseNotifyPayload(array $payload, $secretKey)
    {
        if (!$this->verifyNotifyPayload($payload, $secretKey)) {
            return null;
        }

        return $payload;
    }

    /**
     * Response content for successful notify.
     *
     * @return string
     */
    public function successNotifyResponse()
    {
        return 'ok';
    }
}
