<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

//delete existing updates
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();
$resourceModel = $objectManager->create(\Magento\Staging\Model\ResourceModel\Update::class);
$entityTable = $resourceModel->getTable('staging_update');
$connection->query("DELETE FROM {$entityTable};");
