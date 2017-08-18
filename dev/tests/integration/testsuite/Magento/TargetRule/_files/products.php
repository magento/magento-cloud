<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$objectManager->get(
    \Magento\Framework\View\DesignInterface::class
)->setArea(
    'frontend'
)->setDefaultDesignTheme();

/** @var $productOne \Magento\Catalog\Model\Product */
$productOne = $objectManager->create(\Magento\Catalog\Model\Product::class);
$productOne->setTypeId(
    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
)->setAttributeSetId(
    4
)->setWebsiteIds(
    [$objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getWebsiteId()]
)->setSku(
    'simple_product_1'
)->setName(
    'Simple Product 1 Name'
)->setDescription(
    'Simple Product 1 Full Description'
)->setShortDescription(
    'Simple Product 1 Short Description'
)->setPrice(
    1234.56
)->setTaxClassId(
    2
)->setStockData(
    [
        'use_config_manage_stock'   => 1,
        'qty'                       => 99,
        'is_qty_decimal'            => 0,
        'is_in_stock'               => 1,
    ]
)->setMetaTitle(
    'Simple Product 1 Meta Title'
)->setMetaKeyword(
    'Simple Product 1 Meta Keyword'
)->setMetaDescription(
    'Simple Product 1 Meta Description'
)->setVisibility(
    \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
)->setStatus(
    \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
)->save();

/** @var $productTwo \Magento\Catalog\Model\Product */
$productTwo = $objectManager->create(\Magento\Catalog\Model\Product::class);
$productTwo->setTypeId(
    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
)->setAttributeSetId(
    4
)->setWebsiteIds(
    [$objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getWebsiteId()]
)->setSku(
    'simple_product_2'
)->setName(
    'Simple Product 2 Name'
)->setDescription(
    'Simple Product 2 Full Description'
)->setShortDescription(
    'Simple Product 2 Short Description'
)->setPrice(
    987.65
)->setTaxClassId(
    2
)->setStockData(
    ['use_config_manage_stock' => 1, 'qty' => 0, 'is_in_stock' => 0]
)->setVisibility(
    \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
)->setStatus(
    \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
)->save();

/** @var $productThree \Magento\Catalog\Model\Product */
$productThree = $objectManager->create(\Magento\Catalog\Model\Product::class);
$productThree->setTypeId(
    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
)->setAttributeSetId(
    4
)->setWebsiteIds(
    [$objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore()->getWebsiteId()]
)->setSku(
    'simple_product_3'
)->setName(
    'Simple Product 3 Name'
)->setDescription(
    'Simple Product 3 Full Description'
)->setShortDescription(
    'Simple Product 3 Short Description'
)->setPrice(
    987.65
)->setTaxClassId(
    2
)->setStockData(
    ['use_config_manage_stock' => 1, 'qty' => 24, 'is_in_stock' => 1]
)->setVisibility(
    \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
)->setStatus(
    \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
)->save();
