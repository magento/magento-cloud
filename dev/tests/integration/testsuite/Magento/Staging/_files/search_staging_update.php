<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

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
        $entityIdField => 1,
        'rollback_id' => null,
        'is_rollback' => null,
        'name' => 'Permanent update 1'
    ],
    [
        $entityIdField => 100,
        'rollback_id' => null,
        'is_rollback' => null,
        'name' => 'Permanent update 100'
    ],
];

foreach ($updates as $update) {
    $connection->query(
        "INSERT INTO {$entityTable} (`{$entityIdField}`, `rollback_id`, `is_rollback`, `name`)"
        . " VALUES (:{$entityIdField}, :rollback_id, :is_rollback, :name);",
        $update
    );
}
