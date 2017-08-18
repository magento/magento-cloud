<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

\Magento\TestFramework\Helper\Bootstrap::getInstance()
    ->loadArea(\Magento\Backend\App\Area\FrontNameResolver::AREA_CODE);
$user = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\User\Model\User::class);
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

$role = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Authorization\Model\Role::class);
$role->setName('newrole')->save();
