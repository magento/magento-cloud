<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftWrapping\Block\Adminhtml\Order\Create;

/**
 * @magentoAppArea adminhtml
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class TotalsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Magento\Framework\ObjectManagerInterface */
    private $objectManager;

    /** @var \Magento\Sales\Block\Adminhtml\Order\Create\Totals */
    private $orderCreateBlock;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * test Totals when Giftwrapping added to and removed from quote
     *
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/ConfigurableProduct/_files/tax_rule.php
     * @magentoDataFixture Magento/GiftWrapping/_files/wrapping.php
     * @magentoDataFixture Magento/GiftWrapping/_files/quote_with_giftwrapping.php
     *
     * @param array $giftWrappingQuote
     * @param array $expected
     * @dataProvider getTestTotalsDataProvider
     */
    public function testTotals($giftWrappingQuote, $expected)
    {
        $quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $quote->load('test01', 'reserved_order_id');
        $quote->setIsMultiShipping('0')
            ->setGwId($giftWrappingQuote['quote_gw']['gw_Id'])
            ->setGwPrice($giftWrappingQuote['quote_gw']['gw_price'])
            ->setGwPriceInclTax($giftWrappingQuote['quote_gw']['gw_price_incl_tax'])
            ->setGwBasePrice($giftWrappingQuote['quote_gw']['gw_base_price'])
            ->setGwBasePriceInclTax($giftWrappingQuote['quote_gw']['gw_base_price_incl_tax'])
            ->save();
        $shippingAddress = $quote->getShippingAddress();
        $shippingAddress->setGwId($giftWrappingQuote['quote_gw']['gw_Id'])
            ->setGwPrice($giftWrappingQuote['quote_gw']['gw_price'])
            ->setGwPriceInclTax($giftWrappingQuote['quote_gw']['gw_price_incl_tax'])
            ->setGwBasePrice($giftWrappingQuote['quote_gw']['gw_base_price'])
            ->setGwBasePriceInclTax($giftWrappingQuote['quote_gw']['gw_base_price_incl_tax'])
            ->save();
        $this->orderCreateBlock = $this->getMockBuilder(\Magento\Sales\Block\Adminhtml\Order\Create\Totals::class)
            ->disableOriginalConstructor()
            ->setMethods(['getQuote'])
            ->getMock();
        $this->orderCreateBlock->expects($this->any())->method('getQuote')->will($this->returnValue($quote));
        $totals = $this->orderCreateBlock->getTotals();

        $this->assertEquals(
            $expected['subtotal']['value_excl_tax'],
            $totals['subtotal']['value_excl_tax']
        );
        $this->assertEquals(
            $expected['subtotal']['value_incl_tax'],
            $totals['subtotal']['value_incl_tax']
        );
        $this->assertEquals(
            $expected['subtotal']['value'],
            $totals['subtotal']['value']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_price'],
            $totals['giftwrapping']['gw_price']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_price_incl_tax'],
            $totals['giftwrapping']['gw_price_incl_tax']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_base_price'],
            $totals['giftwrapping']['gw_base_price']
        );
        $this->assertEquals(
            $expected['giftwrapping']['gw_base_price_incl_tax'],
            $totals['giftwrapping']['gw_base_price_incl_tax']
        );
        $this->assertEquals(
            $expected['shipping']['value'],
            $totals['shipping']['value']
        );
        $this->assertEquals(
            $expected['tax']['value'],
            $totals['tax']['value']
        );
        $this->assertEquals(
            $expected['grand_total']['value'],
            $totals['grand_total']['value']
        );
    }

    /**
     * @return array
     */
    public function getTestTotalsDataProvider()
    {
        $gwAddedToQuote = [
            'quote_gw' => [
                'gw_Id' => 1,
                'gw_price' => null,
                'gw_price_incl_tax' => null,
                'gw_base_price' => null,
                'gw_base_price_incl_tax' => null,
            ]
        ];
        $gwRemovedFromQuote = [
            'quote_gw' => [
                'gw_Id' => null,
                'gw_price' => 5,
                'gw_price_incl_tax' => 5.5,
                'gw_base_price' => 5,
                'gw_base_price_incl_tax' => 5.5,
            ]
        ];
        $expectedTotalsWhenGwAddedToQuote = [
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
        $expectedTotalsWhenGwRemovedFromQuote = [
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
        return [
            'gw_added_to_quote' => [
                $gwAddedToQuote,
                $expectedTotalsWhenGwAddedToQuote,
            ],
            'gw_removed_from_quote' => [
                $gwRemovedFromQuote,
                $expectedTotalsWhenGwRemovedFromQuote,
            ],
        ];
    }
}
