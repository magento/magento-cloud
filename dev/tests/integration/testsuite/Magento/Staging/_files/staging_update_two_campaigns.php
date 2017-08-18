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
        $entityIdField => 2,
        'name' => 'Campaign 1',
        'rollback_id' => 100,
        'is_campaign' => 1
    ],
    [
        $entityIdField => 100,
        'rollback_id' => null,
        'is_rollback' => 1,
        'name' => 'Rollback fpr Campaign 1',
    ],
    [
        $entityIdField => 101,
        'name' => 'Campaign 2',
        'rollback_id' => 200,
        'is_campaign' => 1
    ],
    [
        $entityIdField => 200,
        'rollback_id' => null,
        'is_rollback' => 1,
        'name' => 'Rollback fpr Campaign 2',
    ],
];

foreach ($updates as $update) {
    if (!isset($update['is_campaign'])) {
        $connection->query(
            "INSERT INTO {$entityTable} (`{$entityIdField}`, `rollback_id`, `is_rollback`, `name`)"
            . " VALUES (:{$entityIdField}, :rollback_id, :is_rollback, :name);",
            $update
        );
    } else {
        $connection->query(
            "INSERT INTO {$entityTable} (`{$entityIdField}`, `name`, `rollback_id`, `is_campaign`)"
            . " VALUES (:{$entityIdField}, :name, :rollback_id, :is_campaign);",
            $update
        );
    }
}
