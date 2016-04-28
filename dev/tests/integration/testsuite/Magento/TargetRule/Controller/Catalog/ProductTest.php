<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\TargetRule\Controller\Catalog;

class ProductTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * Covers Magento/TargetRule/view/frontend/catalog/product/list/related.html
     * Checks if related products are displayed
     *
     * @magentoDataFixture Magento/Catalog/controllers/_files/products.php
     * @magentoDataFixture Magento/TargetRule/_files/related.php
     */
    public function testProductViewActionRelated()
    {
        $this->dispatch('catalog/product/view/id/1');
        $content = $this->getResponse()->getBody();
        $this->assertContains('<div class="block related"', $content);
        $this->assertContains('Simple Product 2 Name', $content);
    }

    /**
     * Covers Magento/TargetRule/view/frontend/catalog/product/list/upsell.html
     * Checks if up-sell products are displayed
     *
     * @magentoDataFixture Magento/Catalog/controllers/_files/products.php
     * @magentoDataFixture Magento/TargetRule/_files/upsell.php
     */
    public function testProductViewActionUpsell()
    {
        $this->dispatch('catalog/product/view/id/1');
        $content = $this->getResponse()->getBody();
        $this->assertContains('<div class="block upsell"', $content);
        $this->assertContains('Simple Product 2 Name', $content);
    }
}
