<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection('default');
$pkColumnConfig = ['unsigned' => true, 'nullable' => false, 'primary' => true];
$refColumnConfig = ['unsigned' => true, 'nullable' => true];

/** @var \Magento\Framework\DB\Ddl\Table $entityTable */

/** Create table: base_test_entity */
$entityTable = $objectManager->create(\Magento\Framework\DB\Ddl\Table::class);
$entityTable->setName($connection->getTableName('base_test_entity'));
$entityTable->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $pkColumnConfig);
$entityTable->addColumn('reference_col', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $refColumnConfig);
$connection->createTable($entityTable);

/** Create table: parent_of_test_entity_one */
$entityTable = $objectManager->create(\Magento\Framework\DB\Ddl\Table::class);
$entityTable->setName($connection->getTableName('parent_of_test_entity_one'));
$entityTable->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $pkColumnConfig);
$entityTable->addColumn('reference_col', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $refColumnConfig);
$entityTable->addForeignKey(
    'parent_of_test_entity_one_to_base_test_entity',
    'reference_col',
    'base_test_entity',
    'entity_id',
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
);
$connection->createTable($entityTable);

/** Create table: test_entity_one */
$entityTable = $objectManager->create(\Magento\Framework\DB\Ddl\Table::class);
$entityTable->setName($connection->getTableName('test_entity_one'));
$entityTable->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $pkColumnConfig);
$entityTable->addColumn('reference_col', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $refColumnConfig);
$entityTable->addForeignKey(
    'test_entity_one_to_parent',
    'reference_col',
    'parent_of_test_entity_one',
    'entity_id',
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE,
    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
);
$connection->createTable($entityTable);

/** Create table: test_entity_two */
$entityTable = $objectManager->create(\Magento\Framework\DB\Ddl\Table::class);
$entityTable->setName($connection->getTableName('test_entity_two'));
$entityTable->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $pkColumnConfig);
$entityTable->addColumn('reference_col', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $refColumnConfig);
$connection->createTable($entityTable);

/** Create table: test_entity_three */
$entityTable = $objectManager->create(\Magento\Framework\DB\Ddl\Table::class);
$entityTable->setName($connection->getTableName('test_entity_three'));
$entityTable->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $pkColumnConfig);
$entityTable->addColumn('reference_col', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $refColumnConfig);
$connection->createTable($entityTable);

/** Create table: test_entity_four */
$entityTable = $objectManager->create(\Magento\Framework\DB\Ddl\Table::class);
$entityTable->setName($connection->getTableName('test_entity_four'));
$entityTable->addColumn('entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $pkColumnConfig);
$entityTable->addColumn('reference_col', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, $refColumnConfig);
$connection->createTable($entityTable);
