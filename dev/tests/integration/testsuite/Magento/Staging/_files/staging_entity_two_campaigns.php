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
    //Rollbacks and updates for campaign1
    [
        'row_id' => 1,
        $entityIdField => 1,
        'created_in' => 2,
        'updated_in' => 100,
        'name' => 'Entity 1 in Campaign 1'
    ],
    [
        'row_id' => 2,
        $entityIdField => 1,
        'created_in' => 100,
        'updated_in' => 101,
        'name' => 'Rollback 1 in Campaign 1'
    ],
    [
        'row_id' => 3,
        $entityIdField => 2,
        'created_in' => 2,
        'updated_in' => 100,
        'name' => 'Entity 2 in Campaign 1'
    ],
    [
        'row_id' => 4,
        $entityIdField => 2,
        'created_in' => 100,
        'updated_in' => 101,
        'name' => 'Rollback 2 in Campaign 1'
    ],
    //Rolblacks and entities for cmapaign 2
    [
        'row_id' => 5,
        $entityIdField => 1,
        'created_in' => 101,
        'updated_in' => 200,
        'name' => 'Entity 1 in Campaign 2'
    ],
    [
        'row_id' => 6,
        $entityIdField => 1,
        'created_in' => 200,
        'updated_in' => 201,
        'name' => 'Rollback 1 in Campaign 2'
    ],
    [
        'row_id' => 7,
        $entityIdField => 2,
        'created_in' => 101,
        'updated_in' => 200,
        'name' => 'Entity 2 in Campaign 2'
    ],
    [
        'row_id' => 8,
        $entityIdField => 2,
        'created_in' => 200,
        'updated_in' => 201,
        'name' => 'Rollback 2 in Campaign 2'
    ],
];

$connection->query(
    "INSERT INTO {$sequenceTable} (`sequence_value`) VALUES (1), (2), (3), (4), (5), (6), (7), (8);"
);

foreach ($updates as $update) {
    $connection->query(
        "INSERT INTO {$entityTable} (`row_id`, `{$entityIdField}`, `created_in`, `updated_in`, `name`)"
        . " VALUES (:row_id, :{$entityIdField}, :created_in, :updated_in, :name);",
        $update
    );
}
