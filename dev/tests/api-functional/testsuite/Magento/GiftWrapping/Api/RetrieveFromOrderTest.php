<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Api;

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
     * @magentoApiDataFixture Magento/GiftWrapping/_files/order_with_giftwrapping.php
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
        $this->assertOrder($result);
        $this->assertOrderItem(
            current($result['items'])
        );
    }

    /**
     * @param $result
     * @return void
     */
    private function assertOrder($result)
    {
        $this->assertArrayHasKey('gw_id', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_allow_gift_receipt', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_add_card', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_price', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_price', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_price', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_price', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_price', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_price', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_tax_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_tax_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_tax_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_tax_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_tax_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_tax_amount', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_price_incl_tax', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_price_incl_tax', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_price_incl_tax', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_price_incl_tax', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_price_incl_tax', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_price_incl_tax', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_price_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_price_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_price_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_price_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_price_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_price_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_tax_amount_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_tax_amount_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_tax_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_tax_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_tax_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_tax_invoiced', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_price_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_price_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_price_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_price_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_price_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_price_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_base_tax_amount_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_tax_amount_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_base_tax_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_items_tax_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_base_tax_refunded', $result['extension_attributes']);
        $this->assertArrayHasKey('gw_card_tax_refunded', $result['extension_attributes']);
        $this->assertEquals(10, $result['extension_attributes']['gw_id']);
        $this->assertEquals(10, $result['extension_attributes']['gw_allow_gift_receipt']);
        $this->assertEquals(10, $result['extension_attributes']['gw_add_card']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_price']);
        $this->assertEquals(10, $result['extension_attributes']['gw_price']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_price']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_price']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_price']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_price']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_tax_amount']);
        $this->assertEquals(10, $result['extension_attributes']['gw_tax_amount']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_tax_amount']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_tax_amount']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_tax_amount']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_tax_amount']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_price_incl_tax']);
        $this->assertEquals(10, $result['extension_attributes']['gw_price_incl_tax']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_price_incl_tax']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_price_incl_tax']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_price_incl_tax']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_price_incl_tax']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_price_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_price_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_price_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_price_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_price_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_price_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_tax_amount_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_tax_amount_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_tax_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_tax_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_tax_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_tax_invoiced']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_price_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_price_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_price_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_price_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_price_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_price_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_base_tax_amount_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_tax_amount_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_base_tax_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_items_tax_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_base_tax_refunded']);
        $this->assertEquals(10, $result['extension_attributes']['gw_card_tax_refunded']);
    }

    /**
     * @param $orderItem
     * @return void
     */
    private function assertOrderItem($orderItem)
    {
        $this->assertNotEmpty($orderItem);
        $this->assertArrayHasKey('gw_id', $orderItem);
        $this->assertArrayHasKey('gw_base_price', $orderItem);
        $this->assertArrayHasKey('gw_price', $orderItem);
        $this->assertArrayHasKey('gw_base_tax_amount', $orderItem);
        $this->assertArrayHasKey('gw_tax_amount', $orderItem);
        $this->assertArrayHasKey('gw_base_price_invoiced', $orderItem);
        $this->assertArrayHasKey('gw_price_invoiced', $orderItem);
        $this->assertArrayHasKey('gw_base_tax_amount_invoiced', $orderItem);
        $this->assertArrayHasKey('gw_tax_amount_invoiced', $orderItem);
        $this->assertArrayHasKey('gw_base_price_refunded', $orderItem);
        $this->assertArrayHasKey('gw_price_refunded', $orderItem);
        $this->assertArrayHasKey('gw_base_tax_amount_refunded', $orderItem);
        $this->assertArrayHasKey('gw_tax_amount_refunded', $orderItem);
        $this->assertEquals(10, $orderItem['gw_id']);
        $this->assertEquals(10, $orderItem['gw_base_price']);
        $this->assertEquals(10, $orderItem['gw_price']);
        $this->assertEquals(10, $orderItem['gw_base_tax_amount']);
        $this->assertEquals(10, $orderItem['gw_tax_amount']);
        $this->assertEquals(10, $orderItem['gw_base_price_invoiced']);
        $this->assertEquals(10, $orderItem['gw_price_invoiced']);
        $this->assertEquals(10, $orderItem['gw_base_tax_amount_invoiced']);
        $this->assertEquals(10, $orderItem['gw_tax_amount_invoiced']);
        $this->assertEquals(10, $orderItem['gw_base_price_refunded']);
        $this->assertEquals(10, $orderItem['gw_price_refunded']);
        $this->assertEquals(10, $orderItem['gw_base_tax_amount_refunded']);
        $this->assertEquals(10, $orderItem['gw_tax_amount_refunded']);
    }
}
