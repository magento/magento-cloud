<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomerBalance\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class RetrieveFromOrderTest
 */
class RetrieveFromOrderTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/orders';

    const SERVICE_READ_NAME = 'salesOrderRepositoryV1';

    const SERVICE_VERSION = 'V1';

    const ORDER_INCREMENT_ID = '100000001';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/CustomerBalance/_files/order_with_customer_balance.php
     */
    public function testOrderGet()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class);
        $order->loadByIncrementId(self::ORDER_INCREMENT_ID);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $order->getId(),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'get',
            ],
        ];
        $result = $this->_webApiCall($serviceInfo, ['id' => $order->getId()]);
        $this->assertArrayHasKey('extension_attributes', $result);

        $this->assertArrayHasKey('base_customer_balance_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('customer_balance_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('base_customer_balance_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('customer_balance_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('base_customer_balance_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('customer_balance_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('base_customer_balance_total_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('customer_balance_total_refunded', $result['extension_attributes']);
        $this->assertEquals(16, $result['extension_attributes']['base_customer_balance_amount']);
        $this->assertEquals(16, $result['extension_attributes']['customer_balance_amount']);
    }
}
