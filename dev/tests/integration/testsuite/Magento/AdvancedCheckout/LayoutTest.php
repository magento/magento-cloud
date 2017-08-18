<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout;

class LayoutTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @magentoAppArea frontend
     */
    public function testCartLayout()
    {
        \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\DesignInterface::class
        )->setDesignTheme(
            'Magento/luma'
        );
        $layout = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            \Magento\Framework\View\LayoutInterface::class
        );
        $layout->getUpdate()->addHandle('checkout_cart_index');
        $layout->getUpdate()->load();
        $this->assertNotEmpty($layout->getUpdate()->asSimplexml()->xpath('//block[@name="sku.failed.products"]'));
    }
}
