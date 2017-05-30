<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$scope = 'stores';
include __DIR__ . '/role_websites_login.php';

$role = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Authorization\Model\Role::class);
$role
    ->setName('admingws_admin')
    ->setGwsIsAll(1)
    ->setRoleType('G')
    ->setPid('1')
    ->save();

