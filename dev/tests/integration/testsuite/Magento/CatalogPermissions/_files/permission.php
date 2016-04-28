<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $permission \Magento\CatalogPermissions\Model\Permission */
$permission = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    'Magento\CatalogPermissions\Model\Permission'
);
$permission->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        'Magento\Store\Model\StoreManagerInterface'
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
    'Magento\CatalogPermissions\Model\Permission'
);
$permissionAllow->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        'Magento\Store\Model\StoreManagerInterface'
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
    'Magento\Catalog\Setup\CategorySetup'
);

/** @var $product \Magento\Catalog\Model\Product */
$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Product');
$product->setTypeId(
    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
)->setId(
    5
)->setAttributeSetId(
    $installer->getAttributeSetId('catalog_product', 'Default')
)->setStoreId(
    1
)->setWebsiteIds(
    [1]
)->setName(
    'Simple Product Two Permission Test'
)->setSku(
    '12345'
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
