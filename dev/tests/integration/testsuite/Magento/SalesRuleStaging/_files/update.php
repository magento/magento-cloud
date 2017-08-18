<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/SalesRule/_files/cart_rule_free_shipping.php';

//create update
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();
$resourceModel = $objectManager->create(\Magento\Staging\Model\ResourceModel\Update::class);
$entityTable = $resourceModel->getTable('staging_update');
$updateDatetime = new \DateTime('now');
$update =
    [
        'id' => 2,
        'start_time' => $updateDatetime->format('m/d/Y H:i:s'),
        'name' => 'Update name',
        'is_campaign' => 0
    ];
$connection->query(
    "INSERT INTO {$entityTable} (`id`,  `start_time`, `name`, `is_campaign`)"
    . " VALUES (:id, :start_time, :name, :is_campaign);",
    $update
);

//update existing Sales Rule entity
$resourceModel = $objectManager->create(\Magento\SalesRule\Model\ResourceModel\Rule::class);
$entityTable = $resourceModel->getTable('salesrule');

$registry = $objectManager->get(\Magento\Framework\Registry::class);
$salesRule = $registry->registry('cart_rule_free_shipping');
$rowId = $salesRule->getRowId();
$connection->query(
    "UPDATE {$entityTable}  SET  created_in = 2 WHERE row_id = {$rowId}"
);
