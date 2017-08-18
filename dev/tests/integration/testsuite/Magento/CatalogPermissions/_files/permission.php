<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

\Magento\TestFramework\Helper\Bootstrap::getInstance()->reinitialize();
/** @var $permission \Magento\CatalogPermissions\Model\Permission */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$productRepository = $objectManager->create(
    \Magento\Catalog\Api\ProductRepositoryInterface::class
);

$categoryLinkRepository = $objectManager->create(
    \Magento\Catalog\Api\CategoryLinkRepositoryInterface::class,
    [
        'productRepository' => $productRepository
    ]
);

/** @var Magento\Catalog\Api\CategoryLinkManagementInterface $linkManagement */
$categoryLinkManagement = $objectManager->create(
    \Magento\Catalog\Api\CategoryLinkManagementInterface::class,
    [
        'productRepository' => $productRepository,
        'categoryLinkRepository' => $categoryLinkRepository
    ]
);

$permission = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CatalogPermissions\Model\Permission::class
);
$permission->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getWebsite()->getId()
)->setCategoryId(
    6
)->setCustomerGroupId(
    1
)->setGrantCatalogCategoryView(
    \Magento\CatalogPermissions\Model\Permission::PERMISSION_DENY
)->setGrantCatalogProductPrice(
    \Magento\CatalogPermissions\Model\Permission::PERMISSION_DENY
)->setGrantCheckoutItems(
    \Magento\CatalogPermissions\Model\Permission::PERMISSION_DENY
)->save();

/** @var $permissionAllow \Magento\CatalogPermissions\Model\Permission */
$permissionAllow = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CatalogPermissions\Model\Permission::class
);
$permissionAllow->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getWebsite()->getId()
)->setCategoryId(
    12
)->setCustomerGroupId(
    null
)->setGrantCatalogCategoryView(
    \Magento\CatalogPermissions\Model\Permission::PERMISSION_ALLOW
)->setGrantCatalogProductPrice(
    \Magento\CatalogPermissions\Model\Permission::PERMISSION_ALLOW
)->setGrantCheckoutItems(
    \Magento\CatalogPermissions\Model\Permission::PERMISSION_ALLOW
)->save();

$installer = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Catalog\Setup\CategorySetup::class
);

/** @var $product \Magento\Catalog\Model\Product */
$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->setTypeId(
    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
)->setId(
    155
)->setAttributeSetId(
    $installer->getAttributeSetId('catalog_product', 'Default')
)->setStoreId(
    1
)->setWebsiteIds(
    [1]
)->setName(
    'Simple Product Two Permission Test'
)->setSku(
    '12345-1'
)->setPrice(
    45.67
)->setWeight(
    56
)->setStockData(
    ['use_config_manage_stock' => 0]
)->setCategoryIds(
    [6]
)->setVisibility(
    \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
)->setStatus(
    \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
)->save();
$categoryLinkManagement->assignProductToCategories(
    $product->getSku(),
    [6]
);
