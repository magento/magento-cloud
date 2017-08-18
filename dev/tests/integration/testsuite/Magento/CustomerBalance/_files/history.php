<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

\Magento\TestFramework\Helper\Bootstrap::getInstance()->loadArea(
    \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE
);

require __DIR__ . '/../../../Magento/Customer/_files/customer.php';
/** @var $balance \Magento\CustomerBalance\Model\Balance */
$balance = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance::class
);
$balance->setCustomerId(
    $customer->getId()
)->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
);
$balance->save();

/** @var $history \Magento\CustomerBalance\Model\Balance\History */
$history = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerBalance\Model\Balance\History::class
);
$history->setCustomerId(
    $customer->getId()
)->setWebsiteId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getWebsiteId()
)->setBalanceModel(
    $balance
);
$history->save();
