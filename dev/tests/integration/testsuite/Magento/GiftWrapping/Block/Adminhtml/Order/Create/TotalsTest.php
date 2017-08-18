<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftWrapping\Block\Adminhtml\Order\Create;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\Quote\Model\Quote;
use Magento\Sales\Block\Adminhtml\Order\Create\Totals;
use Magento\GiftWrapping\Model\Wrapping;
use Magento\GiftWrapping\Model\ResourceModel\Wrapping as WrappingResource;
use Magento\Store\Model\Store;

/**
 * Test order totals with gift wrapping
 *
 * @magentoAppArea adminhtml
 */
class TotalsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/ConfigurableProduct/_files/tax_rule.php
     * @magentoDataFixture Magento/GiftWrapping/_files/quote_with_giftwrapping.php
     */
    public function testGetTotals()
    {
        /** @var Wrapping $wrapping */
        $wrapping = $this->objectManager->create(Wrapping::class);
        /** @var WrappingResource $wrappingResource */
        $wrappingResource = $this->objectManager->create(WrappingResource::class);
        $wrapping->setDesign('Test wrapping')
            ->setStatus(1)
            ->setBasePrice(5.00)
            ->setImage('image.png')
            ->setStoreId(Store::DEFAULT_STORE_ID);
        $wrappingResource->save($wrapping);
        $wrappingData = [
            'quote_gw' => [
                'gw_id' => $wrapping->getWrappingId(),
                'gw_price' => null,
                'gw_price_incl_tax' => null,
                'gw_base_price' => null,
                'gw_base_price_incl_tax' => null,
            ]
        ];
        $expected = [
            'subtotal' => [
                'value_excl_tax' => 10,
                'value_incl_tax' => 11,
                'value' => 11,
            ],
            'giftwrapping' => [
                'gw_price' => 5,
                'gw_price_incl_tax' => 5.5,
                'gw_base_price' => 5.0,
                'gw_base_price_incl_tax' => 5.5,
            ],
            'shipping' => [
                'value' => 0,
            ],
            'tax' => [
                'value' => 1.5,
            ],
            'grand_total' => [
                'value' => 16.5,
            ],
        ];
        $quote = $this->prepareQuote($wrappingData);
        $orderCreateBlock = $this->getMockBuilder(Totals::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();
        $orderCreateBlock->expects($this->any())
            ->method('getQuote')
            ->willReturn($quote);
        $this->assertTotals($expected, $orderCreateBlock->getTotals());
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoDataFixture Magento/ConfigurableProduct/_files/tax_rule.php
     * @magentoDataFixture Magento/GiftWrapping/_files/quote_with_giftwrapping.php
     */
    public function testGetTotalsNoWrapping()
    {
        $wrappingData = [
            'quote_gw' => [
                'gw_id' => null,
                'gw_price' => 5,
                'gw_price_incl_tax' => 5.5,
                'gw_base_price' => 5,
                'gw_base_price_incl_tax' => 5.5,
            ]
        ];
        $expected = [
            'subtotal' => [
                'value_excl_tax' => 10,
                'value_incl_tax' => 11,
                'value' => 11,
            ],
            'giftwrapping' => [
                'gw_price' => false,
                'gw_price_incl_tax' => false,
                'gw_base_price' => false,
                'gw_base_price_incl_tax' => false,
            ],
            'shipping' => [
                'value' => 0,
            ],
            'tax' => [
                'value' => 1,
            ],
            'grand_total' => [
                'value' => 11,
            ],
        ];
        $quote = $this->prepareQuote($wrappingData);
        $orderCreateBlock = $this->getMockBuilder(Totals::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();
        $orderCreateBlock->expects($this->any())
            ->method('getQuote')
            ->willReturn($quote);
        $this->assertTotals($expected, $orderCreateBlock->getTotals());
    }

    /**
     * @param array $wrappingData
     * @return Quote
     */
    private function prepareQuote(array $wrappingData)
    {
        $quote = $this->objectManager->create(Quote::class);
        $quote->load('test01', 'reserved_order_id');
        $quote->setIsMultiShipping('0')
            ->setGwId($wrappingData['quote_gw']['gw_id'])
            ->setGwPrice($wrappingData['quote_gw']['gw_price'])
            ->setGwPriceInclTax($wrappingData['quote_gw']['gw_price_incl_tax'])
            ->setGwBasePrice($wrappingData['quote_gw']['gw_base_price'])
            ->setGwBasePriceInclTax($wrappingData['quote_gw']['gw_base_price_incl_tax'])
            ->save();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setGwId($wrappingData['quote_gw']['gw_id'])
            ->setGwPrice($wrappingData['quote_gw']['gw_price'])
            ->setGwPriceInclTax($wrappingData['quote_gw']['gw_price_incl_tax'])
            ->setGwBasePrice($wrappingData['quote_gw']['gw_base_price'])
            ->setGwBasePriceInclTax($wrappingData['quote_gw']['gw_base_price_incl_tax'])
            ->save();
        return $quote;
    }

    /**
     * @param array $expected
     * @param array $actual
     */
    private function assertTotals(array $expected, array $actual)
    {
        $this->assertEquals(
            $expected['subtotal']['value_excl_tax'],
            $actual['subtotal']['value_excl_tax']
        );
        $this->assertEquals(
            $expected['subtotal']['value_incl_tax'],
            $actual['subtotal']['value_incl_tax']
        );
        $this->assertEquals(
            $expected['subtotal']['value'],
            $actual['subtotal']['value']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_price'],
            $actual['giftwrapping']['gw_price']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_price_incl_tax'],
            $actual['giftwrapping']['gw_price_incl_tax']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_base_price'],
            $actual['giftwrapping']['gw_base_price']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_base_price_incl_tax'],
            $actual['giftwrapping']['gw_base_price_incl_tax']
        );
        $this->assertEquals(
            $expected['shipping']['value'],
            $actual['shipping']['value']
        );
        $this->assertEquals(
            $expected['tax']['value'],
            $actual['tax']['value']
        );
        $this->assertEquals(
            $expected['grand_total']['value'],
            $actual['grand_total']['value']
        );
    }
}
