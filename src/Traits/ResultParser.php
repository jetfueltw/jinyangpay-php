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
        return json_decode($response, true);
    }

    /**
     * Parse JSON format response to array.
     *
     * @param string $response
     * @return array
     */
    public function parseQueryResponse($response)
    {
        return json_decode($response, true);
    }
}
