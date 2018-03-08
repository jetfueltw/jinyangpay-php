<?php

namespace Test;

use Faker\Factory;
use Jetfuel\Jinyangpay\Constants\Channel;
use Jetfuel\Jinyangpay\DigitalPayment;
use Jetfuel\Jinyangpay\TradeQuery;
use Jetfuel\Jinyangpay\Traits\NotifyWebhook;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $merchantId;
    private $secretKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_ID');
        $this->secretKey = getenv('SECRET_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = date('YmdHis').rand(10000, 99999);
        $channel = Channel::WECHAT;
        $amount = 10;
        $notifyUrl = $faker->url;

        $payment = new DigitalPayment($this->merchantId, $this->secretKey);
        $result = $payment->order($tradeNo, $channel, $amount, $notifyUrl);

        var_dump($result);

        $this->assertEquals(1, $result['rspCode']);

        return $tradeNo;
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderFind($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->find($tradeNo);

        var_dump($result);

        $this->assertEquals(1, $result['rspCode']);
    }

    /**
     * @depends testDigitalPaymentOrder
     *
     * @param $tradeNo
     */
    public function testDigitalPaymentOrderIsPaid($tradeNo)
    {
        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }

    public function testTradeQueryFindOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->find($tradeNo);

        $this->assertNull($result);
    }

    public function testTradeQueryIsPaidOrderNotExist()
    {
        $faker = Factory::create();
        $tradeNo = $faker->uuid;

        $tradeQuery = new TradeQuery($this->merchantId, $this->secretKey);
        $result = $tradeQuery->isPaid($tradeNo);

        $this->assertFalse($result);
    }

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'partner'     => '22225',
            'ordernumber' => '150211000000000018',
            'orderstatus' => '1',
            'paymoney'    => '50',
            'sysnumber'   => 'aa123456789',
            'attach'      => '',
            'sign'        => '5e334f7034b93e78fe35a52df446af06',
        ];

        $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'partner'     => '22225',
            'ordernumber' => '150211000000000018',
            'orderstatus' => '1',
            'paymoney'    => '50',
            'sysnumber'   => 'aa123456789',
            'attach'      => '',
            'sign'        => '5e334f7034b93e78fe35a52df446af06',
        ];

        $this->assertEquals([
            'partner'     => '22225',
            'ordernumber' => '150211000000000018',
            'orderstatus' => '1',
            'paymoney'    => '50',
            'sysnumber'   => 'aa123456789',
            'attach'      => '',
            'sign'        => '5e334f7034b93e78fe35a52df446af06',
        ], $mock->parseNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('ok', $mock->successNotifyResponse());
    }
}
