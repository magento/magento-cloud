<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
require __DIR__ . '/../../../Magento/Store/_files/second_website_store_rollback.php';
/** @var \Magento\Authorization\Model\ResourceModel\Role\Collection $roleCollection */
$roleCollection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Authorization\Model\ResourceModel\Role\Collection::class);
$roleCollection->addFieldToFilter('role_name', ['in' => ['admingws_role', 'admingws_admin']]);
/** @var \Magento\Authorization\Model\Role $role */
foreach ($roleCollection as $role) {
    $role->delete();
}
