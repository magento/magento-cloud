<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/Invitation/_files/invitation.php';

$invitation2 = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\Invitation\Model\Invitation::class);

$invitation2->setInvitationDate('2015-08-03 09:13:11');
$invitation2->isObjectNew(true);
$invitation2->setCustomerId(2)->setGroupId(1)->setEmail('invite2@example.com')->setStoreId(1)->setRefferalId(2)->save();
$invitation2->accept(1, 2);
