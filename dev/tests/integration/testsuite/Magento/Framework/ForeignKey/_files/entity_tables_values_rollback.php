<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection('default');

$connection->delete($connection->getTableName('test_entity_four'));
$connection->delete($connection->getTableName('test_entity_three'));
$connection->delete($connection->getTableName('test_entity_two'));
$connection->delete($connection->getTableName('test_entity_one'));
$connection->delete($connection->getTableName('parent_of_test_entity_one'));
$connection->delete($connection->getTableName('base_test_entity'));
