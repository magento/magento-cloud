<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Controller;

/**
 * @magentoDbIsolation enabled
 */
class ProductTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @test
     *
     * @magentoDataFixture Magento/Catalog/controllers/_files/products.php
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled true
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 0
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 0
     */
    public function testViewActionWithoutPriceAndCart()
    {
        $this->dispatch('catalog/product/view/id/1');
        /** @var $objectManager \Magento\TestFramework\ObjectManager */
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var $currentProduct \Magento\Catalog\Model\Product */
        $currentProduct = $objectManager->get('Magento\Framework\Registry')->registry('current_product');
        $this->assertInstanceOf('Magento\Catalog\Model\Product', $currentProduct);
        $this->assertEquals(1, $currentProduct->getId());

        $lastViewedProductId = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
            'Magento\Catalog\Model\Session'
        )->getLastViewedProductId();
        $this->assertEquals(1, $lastViewedProductId);

        $responseBody = $this->getResponse()->getBody();
        /* Product info */
        $this->assertContains('Simple Product 1 Name', $responseBody);
        $this->assertContains('Simple Product 1 Full Description', $responseBody);
        $this->assertContains('Simple Product 1 Short Description', $responseBody);
        /* Stock info */
        $this->assertNotContains('$1,234.56', $responseBody);
        $this->assertContains('In stock', $responseBody);
        $this->assertNotContains('Add to Cart', $responseBody);
    }
}
