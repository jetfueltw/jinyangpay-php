<?php

namespace Jetfuel\Jinyangpay;

class Signature
{
    /**
     * Generate signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generate(array $payload, $secretKey)
    {
        $baseString = self::buildBaseString($payload).$secretKey;
        var_dump($baseString);

        return self::md5Hash($baseString);
    }

    /**
     * Generate query signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generateQuery(array $payload, $secretKey)
    {
        $baseString = self::buildBaseQueryString($payload).$secretKey;
        var_dump($baseString);

        return self::md5Hash($baseString);
    }

    /**
     * Generate notify signature.
     *
     * @param array $payload
     * @param string $secretKey
     * @return string
     */
    public static function generateNotify(array $payload, $secretKey)
    {
        $baseString = self::buildBaseNotifyString($payload).$secretKey;

        return self::md5Hash($baseString);
    }

    /**
     * @param array $payload
     * @param string $secretKey
     * @param string $signature
     * @return bool
     */
    public static function validate(array $payload, $secretKey, $signature)
    {
        return self::generate($payload, $secretKey) === $signature;
    }

    public static function validateNotify(array $payload, $secretKey, $signature)
    {
        return self::generateNotify($payload, $secretKey) === $signature;
    }

    private static function buildBaseString(array $payload)
    {
        ksort($payload);

        $baseString = '';
        foreach ($payload as $key => $value) {
            $baseString .= $key.'='.$value.'&';
        }

        return rtrim($baseString, '&');
    }

    private static function buildBaseQueryString(array $payload)
    {

        return "p1_mchtid={$payload['p1_mchtid']}&p2_signtype={$payload['p2_signtype']}&p3_orderno={$payload['p3_orderno']}&p4_version={$payload['p4_version']}";
    }

    private static function buildBaseNotifyString(array $payload)
    {

        return "partner={$payload['partner']}&ordernumber={$payload['ordernumber']}&orderstatus={$payload['orderstatus']}&paymoney={$payload['paymoney']}";
    }

    private static function md5Hash($data)
    {
        return md5($data);
    }
}
