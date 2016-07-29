<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftRegistry\Controller\Magento\Catalog;

class ProductTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testViewAction()
    {
        $this->getRequest()->setParam('options', \Magento\GiftRegistry\Block\Product\View::FLAG);
        $this->dispatch('catalog/product/view/id/1');
        $body = $this->getResponse()->getBody();
        $this->assertContains('<span>Add to Gift Registry</span>', $body);
        $this->assertContains('http://localhost/index.php/giftregistry/index/cart/', $body);
    }
}
