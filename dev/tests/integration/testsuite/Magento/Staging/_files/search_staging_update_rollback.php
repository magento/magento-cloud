<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

/** @var Magento\CatalogRule\Model\ResourceModel\Rule $resourceModel */
$resourceModel = Bootstrap::getObjectManager()->create(\Magento\Staging\Model\ResourceModel\Update::class);
$entityTable = $resourceModel->getMainTable();

/** @var Magento\Framework\App\ResourceConnection $resource */
$resource = Bootstrap::getObjectManager()->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();

$connection->query("DELETE FROM {$entityTable};");
