<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogPermissions\Controller;

use Magento\CatalogPermissions\Model\Permission;

/**
 * @magentoDbIsolation disabled
 */
class CategoryTest extends \Magento\TestFramework\TestCase\AbstractController
{
    /**
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 0
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 0
     * @magentoDataFixture Magento/CatalogPermissions/_files/category_products.php
     *
     */
    public function testCategoryFilterPriceNotExistsWithGlobalPriceDenied()
    {
        /** @var \Magento\Framework\Module\ModuleList $modules */
        $modules = $this->_objectManager->get(\Magento\Framework\Module\ModuleList::class);
        if (empty($modules->getOne('Magento_LayeredNavigation'))) {
            $this->markTestSkipped('Skipping test, required module Magento_LayeredNavigation is disabled.');
        }

        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->_objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();
        $this->dispatch("catalog/category/view/id/4");
        $responseBody = $this->getResponse()->getBody();
        $this->assertNotContains(
            'catalog/category/view/id/4/?price=100-200',
            $responseBody,
            'Category page should not contain price filter link'
        );
    }

    /**
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 0
     * @magentoDataFixture Magento/CatalogPermissions/_files/category_products.php
     *
     */
    public function testCategoryFilterPriceShownWithGlobalPriceAllowed()
    {
        /** @var \Magento\Framework\Module\ModuleList $modules */
        $modules = $this->_objectManager->get(\Magento\Framework\Module\ModuleList::class);
        if (empty($modules->getOne('Magento_LayeredNavigation'))) {
            $this->markTestSkipped('Skipping test, required module Magento_LayeredNavigation is disabled.');
        }

        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->_objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();
        $this->dispatch("catalog/category/view/id/4");
        $responseBody = $this->getResponse()->getBody();
        $this->assertContains(
            'catalog/category/view/id/4/?price=100-200',
            $responseBody,
            'Expected price filter links are absent on category page'
        );
        $this->assertContains(
            'catalog/category/view/id/4/?price=200-',
            $responseBody,
            'Expected price filter links are absent on category page'
        );
    }

    /**
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 1
     * @magentoDataFixture Magento/CatalogPermissions/_files/category_products.php
     *
     */
    public function testCategoryFilterPriceNotExists()
    {
        /** @var \Magento\Framework\Module\ModuleList $modules */
        $modules = $this->_objectManager->get(\Magento\Framework\Module\ModuleList::class);
        if (empty($modules->getOne('Magento_LayeredNavigation'))) {
            $this->markTestSkipped('Skipping test, required module Magento_LayeredNavigation is disabled.');
        }

        /** @var $permission Permission */
        $permission = $this->_objectManager->create(\Magento\CatalogPermissions\Model\Permission::class);
        $websiteId = $this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)
            ->getWebsite()->getId();
        $permission->setWebsiteId($websiteId)
            ->setCategoryId(4)
            ->setCustomerGroupId(null)
            ->setGrantCatalogCategoryView(Permission::PERMISSION_ALLOW)
            ->setGrantCatalogProductPrice(Permission::PERMISSION_DENY)
            ->setGrantCheckoutItems(Permission::PERMISSION_DENY)
            ->save();
        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->_objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();
        $this->dispatch("catalog/category/view/id/4");
        $responseBody = $this->getResponse()->getBody();
        $this->assertNotContains(
            'catalog/category/view/id/4/?price=100-200',
            $responseBody,
            'Category page should not contain price filter link'
        );
    }

    /**
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/enabled 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_category_view 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_catalog_product_price 1
     * @magentoConfigFixture current_store catalog/magento_catalogpermissions/grant_checkout_items 1
     * @magentoDataFixture Magento/CatalogPermissions/_files/category_products.php
     *
     */
    public function testCategoryFilterPricePresent()
    {
        /** @var \Magento\Framework\Module\ModuleList $modules */
        $modules = $this->_objectManager->get(\Magento\Framework\Module\ModuleList::class);
        if (empty($modules->getOne('Magento_LayeredNavigation'))) {
            $this->markTestSkipped('Skipping test, required module Magento_LayeredNavigation is disabled.');
        }

        /** @var $permission Permission */
        $permission = $this->_objectManager->create(\Magento\CatalogPermissions\Model\Permission::class);
        $websiteId = $this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)
            ->getWebsite()->getId();
        $permission->setWebsiteId($websiteId)
            ->setCategoryId(4)
            ->setCustomerGroupId(null)
            ->setGrantCatalogCategoryView(Permission::PERMISSION_ALLOW)
            ->setGrantCatalogProductPrice(Permission::PERMISSION_ALLOW)
            ->setGrantCheckoutItems(Permission::PERMISSION_ALLOW)
            ->save();
        /** @var  $indexer \Magento\Framework\Indexer\IndexerInterface */
        $indexer = $this->_objectManager->create(\Magento\Indexer\Model\Indexer::class);
        $indexer->load(\Magento\CatalogPermissions\Model\Indexer\Category::INDEXER_ID);
        $indexer->reindexAll();
        $this->dispatch("catalog/category/view/id/4");
        $responseBody = $this->getResponse()->getBody();
        $this->assertContains(
            'catalog/category/view/id/4/?price=100-200',
            $responseBody,
            'Expected price filter links are absent on category page'
        );
        $this->assertContains(
            'catalog/category/view/id/4/?price=200-',
            $responseBody,
            'Expected price filter links are absent on category page'
        );
    }
}
