<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

// @TODO Remove CatalogRule dependency from this script
/**
 * @var $resourceModel Magento\CatalogRule\Model\ResourceModel\Rule
 */
$resourceModel = Bootstrap::getObjectManager()->create('Magento\CatalogRule\Model\ResourceModel\Rule');
$entityTable = $resourceModel->getMainTable();

/**
 * @var $resource Magento\Framework\App\ResourceConnection
 */
$resource = Bootstrap::getObjectManager()->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
$sequenceTable = $resourceModel->getTable('sequence_catalogrule');

$connection->query("DELETE FROM {$entityTable};");
$connection->query("DELETE FROM {$sequenceTable};");
