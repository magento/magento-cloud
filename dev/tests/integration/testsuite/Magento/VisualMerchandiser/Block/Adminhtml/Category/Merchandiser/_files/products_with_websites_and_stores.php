<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$website = $objectManager->create(\Magento\Store\Model\Website::class);
/** @var $website \Magento\Store\Model\Website */
$websiteId = $website->load('test', 'code')->getId();

// Create product only on the second website
$websiteIds = [$websiteId];
$productName = 'Simple Product on second website';
$sku = 'visual merchandiser simple-2';
/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
if (!$product->loadByAttribute('sku', $sku)) {
    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
        ->setAttributeSetId(4)
        ->setWebsiteIds($websiteIds)
        ->setName($productName)
        ->setSku($sku)
        ->setPrice(10)
        ->setDescription('Description with <b>html tag</b>')
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->setCategoryIds([333])
        ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
        ->save();
}

// Create product only on the first website
$websiteIds = [1];
$productName = 'Simple Product';
$sku = 'visual merchandiser simple-1';
/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
if (!$product->loadByAttribute('sku', $sku)) {
    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
        ->setAttributeSetId(4)
        ->setWebsiteIds($websiteIds)
        ->setName($productName)
        ->setSku($sku)
        ->setPrice(10)
        ->setDescription('Description with <b>html tag</b>')
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->setCategoryIds([333])
        ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
        ->save();
}

// Create product on both websites
$websiteIds = [1, $websiteId];
$productName = 'Simple Product on both website';
$sku = 'visual merchandiser simple-3';
/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
if (!$product->loadByAttribute('sku', $sku)) {
    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
        ->setAttributeSetId(4)
        ->setWebsiteIds($websiteIds)
        ->setName($productName)
        ->setSku($sku)
        ->setPrice(10)
        ->setDescription('Description with <b>html tag</b>')
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->setCategoryIds([333])
        ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
        ->save();
}

// Create product without website
$websiteIds = [];
$productName = 'Simple Product without website';
$sku = 'visual merchandiser simple-4';
/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
if (!$product->loadByAttribute('sku', $sku)) {
    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
        ->setAttributeSetId(4)
        ->setWebsiteIds($websiteIds)
        ->setName($productName)
        ->setSku($sku)
        ->setPrice(10)
        ->setDescription('Description with <b>html tag</b>')
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->setCategoryIds([333])
        ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
        ->save();
}
