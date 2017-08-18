<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\CatalogPermissions\Model\Permission;
use Magento\TestFramework\Helper\Bootstrap;

/** @var \Magento\Framework\Registry $registry */
$registry = Bootstrap::getObjectManager()->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var $product \Magento\Catalog\Model\Product */
$product = Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->load(155);
if ($product->getId()) {
    $product->delete();
}

/** @var $permission Permission */
$permission = Bootstrap::getObjectManager()->create(
    Permission::class
);
$permission->getCollection()->load()->walk('delete');
$permission->getCollection()->load()->walk('delete');

// Cleanup indexer table
/** @var $index \Magento\CatalogPermissions\Model\ResourceModel\Permission\Index */
$index = Bootstrap::getObjectManager()->create(
    \Magento\CatalogPermissions\Model\ResourceModel\Permission\Index::class
);
$index->getConnection()->delete(
    $index->getMainTable()
);
$index->getConnection()->delete(
    $index->getMainTable() .
    \Magento\CatalogPermissions\Model\Indexer\AbstractAction::PRODUCT_SUFFIX
);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
