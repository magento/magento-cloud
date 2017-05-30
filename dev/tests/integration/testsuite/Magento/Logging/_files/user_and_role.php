<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

\Magento\TestFramework\Helper\Bootstrap::getInstance()
    ->loadArea(\Magento\Backend\App\Area\FrontNameResolver::AREA_CODE);
$user = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\User\Model\User');
$user->setUsername(
    'newuser'
)->setFirstname(
    'first_name'
)->setLastname(
    'last_name'
)->setPassword(
    'password1'
)->setEmail(
    'newuser@example.com'
)->setRoleId(
    1
)->save();

$role = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Authorization\Model\Role');
$role->setName('newrole')->save();
