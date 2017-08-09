<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require 'products_media.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();

/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setAttributeSetId(4)
    ->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID)
    ->setWebsiteIds([1])
    ->setName('Simple Product 1')
    ->setSku('simple-staging-with-gallery-1')
    ->setPrice(10)
    ->setWeight(1)
    ->setShortDescription("Short description")
    ->setTaxClassId(0)
    ->setDescription('Description with <b>html tag</b>')
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setStockData(
        [
            'use_config_manage_stock' => 1,
            'qty' => 100,
            'is_qty_decimal' => 0,
            'is_in_stock' => 1,
        ]
    )
    ->setCanSaveCustomOptions(true)
    ->save();

// Emulate product custom row_id.
$connection->startSetup();
$connection->update(
    $resource->getTableName('catalog_product_entity'),
    ['row_id' => 10000],
    $connection->quoteInto("entity_id = ?", $product->getId())
);
$connection->endSetup();

// Assign gallery image
$product->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID)
    ->setImage('/i/m/image1.jpg')
    ->setSmallImage('/i/m/image1.jpg')
    ->setThumbnail('/i/m/image1.jpg')
    ->setData(
        'media_gallery',
        [
            'images' => [
                [
                    'file' => '/i/m/image1.jpg',
                    'position' => 0,
                    'label' => 'Image Alt Text',
                    'disabled' => 0,
                    'media_type' => 'image'
                ],
                [
                    'file' => '/i/m/image2.jpg',
                    'position' => 1,
                    'label' => 'Image Alt Text',
                    'disabled' => 0,
                    'media_type' => 'image'
                ],
            ]
        ]
    )
    ->setCanSaveCustomOptions(true)
    ->save();

/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->isObjectNew(true);
$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(10000)
    ->setAttributeSetId(4)
    ->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID)
    ->setWebsiteIds([1])
    ->setName('Simple Product 2')
    ->setSku('simple-staging-with-gallery-2')
    ->setPrice(10)
    ->setWeight(1)
    ->setShortDescription("Short description")
    ->setTaxClassId(0)
    ->setDescription('Description with <b>html tag</b>')
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setStockData(
        [
            'use_config_manage_stock' => 1,
            'qty' => 100,
            'is_qty_decimal' => 0,
            'is_in_stock' => 1,
        ]
    )
    ->setImage('/i/m/image3.jpg')
    ->setSmallImage('/i/m/image3.jpg')
    ->setThumbnail('/i/m/image3.jpg')
    ->setData(
        'media_gallery',
        [
            'images' => [
                [
                    'file' => '/i/m/image3.jpg',
                    'position' => 0,
                    'label' => 'Image Alt Text',
                    'disabled' => 0,
                    'media_type' => 'image'
                ],
                [
                    'file' => '/i/m/image4.jpg',
                    'position' => 1,
                    'label' => 'Image Alt Text',
                    'disabled' => 0,
                    'media_type' => 'image'
                ],
            ]
        ]
    )
    ->setCanSaveCustomOptions(true)
    ->save();
