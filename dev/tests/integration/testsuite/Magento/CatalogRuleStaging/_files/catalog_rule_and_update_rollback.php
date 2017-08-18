<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

//delete existing updates
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \Magento\Framework\App\ResourceConnection $resource */
$resource = $objectManager->get(\Magento\Framework\App\ResourceConnection::class);
$connection = $resource->getConnection();

/** @var \Magento\Staging\Model\ResourceModel\Update $resourceModel */
$resourceModel = $objectManager->create(\Magento\Staging\Model\ResourceModel\Update::class);
$connection->delete($resourceModel->getMainTable());

//delete existing catalog rules
/** @var \Magento\CatalogRule\Model\ResourceModel\Rule $ruleResource */
$ruleResource = $objectManager->create(\Magento\CatalogRule\Model\ResourceModel\Rule::class);
$connection->delete($ruleResource->getMainTable());
