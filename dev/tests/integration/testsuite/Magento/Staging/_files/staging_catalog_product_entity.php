<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Staging\Model\VersionManager;
use Magento\Catalog\Setup\CategorySetup;

$setUpResource = Bootstrap::getObjectManager()->create(
    CategorySetup::class
);

$updates = [
    [
        'row_id' => 10,
        'entity_id' => 1,
        'created_in' => 1,
        'updated_in' => 100,
        'attribute_set_id' => 4
    ],
    [
        'row_id' => 11,
        'entity_id' => 1,
        'created_in' => 100,
        'updated_in' => 200,
        'attribute_set_id' => 4
    ],
    [
        'row_id' => 12,
        'entity_id' => 1,
        'created_in' => 200,
        'updated_in' => 300,
        'attribute_set_id' => 4
    ],
    [
        'row_id' => 13,
        'entity_id' => 1,
        'created_in' => 300,
        'updated_in' => 400,
        'attribute_set_id' => 4
    ],
    [
        'row_id' => 14,
        'entity_id' => 1,
        'created_in' => 400,
        'updated_in' => 500,
        'attribute_set_id' => 4
    ],
    [
        'row_id' => 15,
        'entity_id' => 1,
        'created_in' => 500,
        'updated_in' => 600,
        'attribute_set_id' => 4
    ],
    [
        'row_id' => 16,
        'entity_id' => 1,
        'created_in' => 600,
        'updated_in' => VersionManager::MAX_VERSION,
        'attribute_set_id' => 4
    ],

];

$setUpResource->getSetup()->getConnection()->insertMultiple(
    $setUpResource->getSetup()->getTable('catalog_product_entity'),
    $updates
);
