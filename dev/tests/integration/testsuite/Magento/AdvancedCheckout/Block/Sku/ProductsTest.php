<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout\Block\Sku;

/**
 * @magentoAppArea frontend
 */
class ProductsTest extends \PHPUnit_Framework_TestCase
{
    public function testToHtml()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->get('Magento\Framework\App\State')
            ->setAreaCode('frontend');
        $block = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Framework\View\LayoutInterface'
        )->createBlock(
            'Magento\AdvancedCheckout\Block\Sku\Products'
        )->setTemplate(
            'cart/sku/failed.phtml'
        );
        $this->assertEmpty($block->toHtml());

        $item = ['sku' => 'test', 'code' => \Magento\AdvancedCheckout\Helper\Data::ADD_ITEM_STATUS_FAILED_SKU];
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\AdvancedCheckout\Helper\Data'
        )->getSession()->setAffectedItems(
            [
                \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
                    'Magento\Store\Model\StoreManagerInterface'
                )->getStore()->getId() => [
                    $item
                ]
            ]
        );
        $this->assertContains('<form', $block->toHtml());
    }
}
