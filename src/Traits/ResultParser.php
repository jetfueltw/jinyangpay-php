<?php

namespace Jetfuel\Jinyangpay\Traits;

use Sunra\PhpSimple\HtmlDomParser;

trait ResultParser
{
    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @return string|null
     */
    public function parseResponse($response)
    {
        $result = json_decode($response, true);
        $result['data']['r6_qrcode'] = 'IMG|'.$result['data']['r6_qrcode'];
        
        return $result;
    }

    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @return string|null
     */
    public function parseQueryResponse($response)
    {

        return json_decode($response, true);
    }
}
