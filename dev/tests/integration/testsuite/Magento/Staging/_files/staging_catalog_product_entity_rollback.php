<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

/** @var Magento\CatalogRule\Model\ResourceModel\Rule $resourceModel */
$resourceModel = Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\ResourceModel\Product::class);

$entityTable = $resourceModel->getTable('catalog_product_entity');
$sequenceTable = $resourceModel->getTable('sequence_catalogrule');

/** @var Magento\Framework\App\ResourceConnection $resource */
$resource = Bootstrap::getObjectManager()->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();

$connection->query("DELETE FROM {$entityTable};");
$connection->query("DELETE FROM {$sequenceTable};");
