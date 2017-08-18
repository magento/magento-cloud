<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection('default');

$connection->dropTable($connection->getTableName('test_entity_four'));
$connection->dropTable($connection->getTableName('test_entity_three'));
$connection->dropTable($connection->getTableName('test_entity_two'));
$connection->dropTable($connection->getTableName('test_entity_one'));
$connection->dropTable($connection->getTableName('parent_of_test_entity_one'));
$connection->dropTable($connection->getTableName('base_test_entity'));
