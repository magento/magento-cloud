<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

// @TODO Remove CatalogRule dependency from this script
/**
 * @var $resourceModel Magento\CatalogRule\Model\ResourceModel\Rule
 */
$resourceModel = Bootstrap::getObjectManager()->create(\Magento\CatalogRule\Model\ResourceModel\Rule::class);
$entityTable = $resourceModel->getMainTable();

/**
 * @var $resource Magento\Framework\App\ResourceConnection
 */
$resource = Bootstrap::getObjectManager()->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();
$sequenceTable = $resourceModel->getTable('sequence_catalogrule');

$connection->query("DELETE FROM {$entityTable};");
$connection->query("DELETE FROM {$sequenceTable};");
