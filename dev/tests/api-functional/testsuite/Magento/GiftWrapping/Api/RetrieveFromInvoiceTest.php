<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class RetrieveFromInvoiceTest
 */
class RetrieveFromInvoiceTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/invoices';

    const SERVICE_READ_NAME = 'salesInvoiceRepositoryV1';

    const SERVICE_VERSION = 'V1';

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
    public function testInvoiceGet()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoiceCollection = $objectManager->get(\Magento\Sales\Model\ResourceModel\Order\Invoice\Collection::class);
        $invoice = $invoiceCollection->getFirstItem();
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $invoice->getId(),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'get',
            ],
        ];
        $result = $this->_webApiCall($serviceInfo, ['id' => $invoice->getId()]);
        $this->assertArrayHasKey('extension_attributes', $result);

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
    }
}
