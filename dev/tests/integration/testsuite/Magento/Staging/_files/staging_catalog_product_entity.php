<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Staging\Model\VersionManager;

/** @var \Magento\Catalog\Model\ResourceModel\Product $resourceModel */
$resourceModel = Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\ResourceModel\Product::class);

$updates = [
    [
        'entity_id' => 1,
        'created_in' => 1,
        'updated_in' => 100,
        'attribute_set_id' => 4
    ],
    [
        'entity_id' => 1,
        'created_in' => 100,
        'updated_in' => 200,
        'attribute_set_id' => 4
    ],
    [
        'entity_id' => 1,
        'created_in' => 200,
        'updated_in' => 300,
        'attribute_set_id' => 4
    ],
    [
        'entity_id' => 1,
        'created_in' => 300,
        'updated_in' => 400,
        'attribute_set_id' => 4
    ],
    [
        'entity_id' => 1,
        'created_in' => 400,
        'updated_in' => 500,
        'attribute_set_id' => 4
    ],
    [
        'entity_id' => 1,
        'created_in' => 500,
        'updated_in' => 600,
        'attribute_set_id' => 4
    ],
    [
        'entity_id' => 1,
        'created_in' => 600,
        'updated_in' => VersionManager::MAX_VERSION,
        'attribute_set_id' => 4
    ],

];

$resourceModel->getConnection()->insertMultiple(
    $resourceModel->getTable('catalog_product_entity'),
    $updates
);
