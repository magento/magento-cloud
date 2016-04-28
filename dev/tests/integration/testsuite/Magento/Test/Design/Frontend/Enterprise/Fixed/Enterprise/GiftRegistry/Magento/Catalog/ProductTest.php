<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Design\Frontend\Enterprise\Fixed\Enterprise\GiftRegistry\Magento\Catalog;

class ProductTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture Magento/Bundle/_files/product.php
     */
    public function testViewActionBundle()
    {
        $this->getRequest()->setParam('options', \Magento\GiftRegistry\Block\Product\View::FLAG);
        $this->dispatch('catalog/product/view/id/3');
        $body = $this->getResponse()->getBody();
        $this->assertContains('<span>Customize and Add to Gift Registry</span>', $body);
        $this->assertContains('<span>Add to Gift Registry</span>', $body);
        $this->assertContains('http://localhost/index.php/giftregistry/index/cart/', $body);
    }
}
