<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var invitation \Magento\Invitation\Model\Invitation */
$invitation = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\Invitation\Model\Invitation::class);

$invitation->setInvitationDate('2015-08-03 09:13:11');
$invitation->isObjectNew(true);
$invitation->setCustomerId(1)->setGroupId(1)->setEmail('invite2@example.com')->setStoreId(1)->save();
$invitation->accept(1, 1);
