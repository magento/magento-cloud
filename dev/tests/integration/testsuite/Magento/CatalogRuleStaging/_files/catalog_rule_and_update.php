<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

//create catalog rule
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var \Magento\CatalogRule\Model\Rule $catalogRule */
$catalogRule = $objectManager->create(\Magento\CatalogRule\Model\Rule::class);
$catalogRule
    ->setRowId(500)
    ->setIsActive(1)
    ->setName('Test Catalog Rule')
    ->setDiscountAmount(10)
    ->setWebsiteIds([0 => 1])
    ->setSimpleAction('by_percent')
    ->setStopRulesProcessing(false)
    ->setSortOrder(0)
    ->setSubIsEnable(0)
    ->setSubDiscountAmount(0)
    ->save();

//create update
/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();
/** @var \Magento\Staging\Model\ResourceModel\Update $resourceModelUpdate */
$resourceModelUpdate = $objectManager->create(\Magento\Staging\Model\ResourceModel\Update::class);
$entityTable = $resourceModelUpdate->getTable('staging_update');
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
/** @var \Magento\CatalogRule\Model\ResourceModel\Rule $resourceModelRule */
$resourceModelRule = $objectManager->create(\Magento\CatalogRule\Model\ResourceModel\Rule::class);
$entityTable = $resourceModelRule->getTable('catalogrule');

$connection->query(
    "UPDATE {$entityTable}  SET  created_in = 2 WHERE row_id = 500"
);
