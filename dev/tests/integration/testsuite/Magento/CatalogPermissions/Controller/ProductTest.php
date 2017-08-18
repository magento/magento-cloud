<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Controller;

/**
 * @magentoDbIsolation disabled
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
        /** @var \Magento\Catalog\Model\ResourceModel\Product $resource */
        $resource = $this->_objectManager->get(\Magento\Catalog\Model\ResourceModel\Product::class);
        $productId = $resource->getConnection()->fetchOne(
            $resource->getConnection()->select()
            ->from(['p' => $resource->getTable('catalog_product_entity')], ['entity_id'])
            ->where('sku = ?', 'simple_product_1')
        );
        $this->dispatch('catalog/product/view/id/' . $productId);

        /** @var $currentProduct \Magento\Catalog\Model\Product */
        $currentProduct = $this->_objectManager->get(\Magento\Framework\Registry::class)->registry('current_product');
        $this->assertInstanceOf(\Magento\Catalog\Model\Product::class, $currentProduct);
        $this->assertEquals($productId, $currentProduct->getId());

        $lastViewedProductId = $this->_objectManager->get(
            \Magento\Catalog\Model\Session::class
        )->getLastViewedProductId();
        $this->assertEquals($productId, $lastViewedProductId);

        $responseBody = $this->getResponse()->getBody();
        //Escape
        preg_replace('/<script\b[^>]*>\b(?:)<\\/script>/s', '', $responseBody);
        /* Product info */
        $this->assertContains('Simple Product 1 Name', $responseBody);
        $this->assertContains('Simple Product 1 Full Description', $responseBody);
        $this->assertContains('Simple Product 1 Short Description', $responseBody);
        $responseBody = preg_replace("/<script.*<\\/script>/", "", $responseBody);
        /* Stock info */
        $this->assertContains('In stock', $responseBody);
        $this->assertNotContains('Add to Cart', $responseBody);
    }
}
