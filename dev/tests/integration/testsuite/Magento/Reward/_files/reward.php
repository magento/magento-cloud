<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/Customer/_files/customer.php';

/** @var $reward \Magento\Reward\Model\Reward */
$reward = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Reward\Model\Reward::class);
$reward->setCustomerId(1)->setWebsiteId(1);
$reward->save();

return $reward;
