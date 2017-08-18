<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Staging\Model\VersionManager;

// @TODO Remove CatalogRule dependency from this script
/**
 * @var $resourceModel Magento\CatalogRule\Model\ResourceModel\Rule
 */
$resourceModel = Bootstrap::getObjectManager()->create(\Magento\CatalogRule\Model\ResourceModel\Rule::class);
$entityIdField = $resourceModel->getIdFieldName();
$entityTable = $resourceModel->getMainTable();

/**
 * @var $resource Magento\Framework\App\ResourceConnection
 */
$resource = Bootstrap::getObjectManager()->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();
$sequenceTable = $resourceModel->getTable('sequence_catalogrule');

$updates = [
    [
        'row_id' => 1,
        $entityIdField => 1,
        'created_in' => 1,
        'updated_in' => 100,
        'name' => 'Entity 1'
    ],
    [
        'row_id' => 2,
        $entityIdField => 1,
        'created_in' => 100,
        'updated_in' => 200,
        'name' => 'Entity 100'
    ],
    [
        'row_id' => 3,
        $entityIdField => 1,
        'created_in' => 200,
        'updated_in' => 300,
        'name' => 'Entity 200'
    ],
    [
        'row_id' => 4,
        $entityIdField => 1,
        'created_in' => 300,
        'updated_in' => 400,
        'name' => 'Entity 100'
    ],
    [
        'row_id' => 5,
        $entityIdField => 1,
        'created_in' => 400,
        'updated_in' => 500,
        'name' => 'Entity 400'
    ],
    [
        'row_id' => 6,
        $entityIdField => 1,
        'created_in' => 500,
        'updated_in' => 600,
        'name' => 'Entity 500'
    ],
    [
        'row_id' => 7,
        $entityIdField => 1,
        'created_in' => 600,
        'updated_in' => VersionManager::MAX_VERSION,
        'name' => 'Entity 400'
    ]
];

$connection->query(
    "INSERT INTO {$sequenceTable} (`sequence_value`) VALUES (1);"
);

foreach ($updates as $update) {
    $connection->query(
        "INSERT INTO {$entityTable} (`row_id`, `{$entityIdField}`, `created_in`, `updated_in`, `name`)"
        . " VALUES (:row_id, :{$entityIdField}, :created_in, :updated_in, :name);",
        $update
    );
}
