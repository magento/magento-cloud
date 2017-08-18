<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Staging\Model\VersionManager;

/**
 * @var $resourceModel Magento\CatalogRule\Model\ResourceModel\Rule
 */
$resourceModel = Bootstrap::getObjectManager()->create(\Magento\Staging\Model\ResourceModel\Update::class);
$entityIdField = $resourceModel->getIdFieldName();
$entityTable = $resourceModel->getMainTable();

/**
 * @var $resource Magento\Framework\App\ResourceConnection
 */
$resource = Bootstrap::getObjectManager()->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();

$updates = [
    [
        $entityIdField => 100,
        'rollback_id' => null,
        'is_rollback' => null,
        'name' => 'Permanent update 100'
    ],
    [
        $entityIdField => 200,
        'rollback_id' => 300,
        'is_rollback' => null,
        'name' => 'Temporary update 200-300'
    ],
    [
        $entityIdField => 300,
        'rollback_id' => null,
        'is_rollback' => 1,
        'name' => 'Rollback Temporary update 200-300'
    ],
    [
        $entityIdField => 400,
        'rollback_id' => null,
        'is_rollback' => null,
        'name' => 'Permanent update 400'
    ],
    [
        $entityIdField => 500,
        'rollback_id' => 600,
        'is_rollback' => null,
        'name' => 'Temporary update 500-600'
    ],
    [
        $entityIdField => 600,
        'rollback_id' => null,
        'is_rollback' => 1,
        'name' => 'Rollback Temporary update 500-600'
    ],
    [
        $entityIdField => 700,
        'rollback_id' => null,
        'is_rollback' => null,
        'name' => 'Permanent update 700 (not assigned to any entity)'
    ],
    [
        $entityIdField => 125,
        'rollback_id' => 175,
        'is_rollback' => null,
        'name' => 'Temporary update 125-175 (not assigned to any entity)'
    ],
    [
        $entityIdField => 175,
        'rollback_id' => null,
        'is_rollback' => 1,
        'name' => 'Rollback Temporary update 125-175 (not assigned to any entity)'
    ],

];

foreach ($updates as $update) {
    $connection->query(
        "INSERT INTO {$entityTable} (`{$entityIdField}`, `rollback_id`, `is_rollback`, `name`)"
        . " VALUES (:{$entityIdField}, :rollback_id, :is_rollback, :name);",
        $update
    );
}
