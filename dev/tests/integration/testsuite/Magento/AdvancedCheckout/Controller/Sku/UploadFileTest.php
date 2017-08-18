<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\AdvancedCheckout\Controller\Sku;

use Magento\AdvancedCheckout\Helper\Data;
use Magento\CatalogPermissions\Model\Indexer\Category;
use Magento\Framework\Indexer\IndexerInterface;
use Magento\TestFramework\TestCase\AbstractController;
use Magento\Customer\Model\Session;

/**
 * @magentoAppArea frontend
 */
class UploadFileTest extends AbstractController
{
    /**
     * Tests that product can not be added to Shopping Cart via Order by SKU as of Catalog Permission settings.
     * Product belongs to category. Access to this category is denied for all customer groups.
     * After Order by SKU product should appear in Products Requiring Attention section with error message.
     *
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 1
     * @magentoDataFixture Magento/Customer/_files/customer.php
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @magentoDataFixture Magento/CatalogPermissions/_files/permission.php
     */
    public function testOrderBySkuProductDeniedByCatalogPermissions()
    {
        $postData = [
            'items' =>  [
                ['sku' => '12345-1']
            ]
        ];
        $customerId = 1;

        $this->reindexCategories();
        /** @var Session $session */
        $session = $this->_objectManager->get(Session::class);
        $session->setCustomerId($customerId);
        $this->getRequest()->setPostValue($postData);

        $this->dispatch('customer_order/sku/uploadFile/');

        $cartFailedItem = $this->getCartFailedItem();

        $this->assertSessionMessages(
            $this->equalTo(['1 product requires your attention.'])
        );
        $this->assertNotNull(
            $cartFailedItem,
            'Product should be present in cart failed items list'
        );
        $this->assertEquals(
            Data::ADD_ITEM_STATUS_FAILED_PERMISSIONS,
            $cartFailedItem->getCode(),
            'Cart item should have failed permissions code'
        );
    }

    /**
     * Returns cart failed item.
     *
     * @return \Magento\Quote\Model\Quote\Item
     */
    private function getCartFailedItem()
    {
        /** @var Data $advancedCheckoutHelper */
        $advancedCheckoutHelper = $this->_objectManager->get(Data::class);
        $cartFailedItems = $advancedCheckoutHelper->getFailedItems();
        /** @var \Magento\Quote\Model\Quote\Item $cartFailedItem */
        $cartFailedItem = array_pop($cartFailedItems);

        return $cartFailedItem;
    }

    /**
     * Reindex categories.
     *
     * @return void
     */
    private function reindexCategories()
    {
        $indexer = $this->_objectManager->create(IndexerInterface::class);
        $indexer->load(Category::INDEXER_ID);
        $indexer->reindexAll();
    }
}
