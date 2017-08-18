<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter $filter */
$filter = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\AdvancedSalesRule\Model\ResourceModel\Rule\Condition\Filter::class
);

$connection = $filter->getConnection();
$filtersSelect = $connection->select()
->from(['e' => $filter->getMainTable()]);
$sql = $filtersSelect->deleteFromSelect('e');
$connection->query($sql);
