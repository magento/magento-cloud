<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../Catalog/_files/product_image.php';

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\ResourceConnection;
use Magento\TestFramework\Helper\Bootstrap;

/** @var Product $product */
$product = Bootstrap::getObjectManager()->create(Product::class);

$product->isObjectNew(true);
$product->setTypeId(Type::TYPE_SIMPLE)
    ->setEntityId(9999)
    ->setAttributeSetId(4)
    ->setWebsiteIds([1])
    ->setName('Simple Product ' . uniqid())
    ->setSku('staging_simple_with_gallery')
    ->setPrice(10)
    ->setDescription('Description with <b>html tag</b>')
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(Visibility::VISIBILITY_BOTH)
    ->setStatus(Status::STATUS_ENABLED)
    ->save();

// Set product custom row_id.
$resource = Bootstrap::getObjectManager()->get(ResourceConnection::class);
$connection = $resource->getConnection();
$tableName = $product->getResource()->getTable('catalog_product_entity');
$connection->query("SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0");
$connection->update(
    $tableName,
    ['row_id' => 10000],
    $connection->quoteInto("entity_id = ?", $product->getId())
);
$connection->query("SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS");

// assign gallery image
$product->setStoreId(0)
    ->setImage('/m/a/magento_image.jpg')
    ->setSmallImage('/m/a/magento_image.jpg')
    ->setThumbnail('/m/a/magento_image.jpg')
    ->setData('media_gallery', [
        'images' => [
            [
                'file' => '/m/a/magento_image.jpg',
                'position' => 1,
                'label' => 'Image Alt Text',
                'disabled' => 0,
                'media_type' => 'image'
            ],
        ]
    ])
    ->save();

$productWithoutGallery = Bootstrap::getObjectManager()->create(Product::class);
$productWithoutGallery->isObjectNew(true);
$productWithoutGallery->setTypeId(Type::TYPE_SIMPLE)
    ->setEntityId($product->getRowId())
    ->setAttributeSetId(4)
    ->setWebsiteIds([1])
    ->setName('Simple Product ' . uniqid())
    ->setSku('staging_simple_without_gallery')
    ->setPrice(10)
    ->setDescription('Description with <b>html tag</b>')
    ->setMetaTitle('meta title')
    ->setMetaKeyword('meta keyword')
    ->setMetaDescription('meta description')
    ->setVisibility(Visibility::VISIBILITY_BOTH)
    ->setStatus(Status::STATUS_ENABLED)
    ->save();
