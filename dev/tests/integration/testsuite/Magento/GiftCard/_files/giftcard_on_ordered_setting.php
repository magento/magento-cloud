<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$config = $objectManager->get(\Magento\Config\Model\Config::class);
$config->setDataByPath(
    \Magento\GiftCard\Model\Giftcard::XML_PATH_ORDER_ITEM_STATUS,
    \Magento\Sales\Model\Order\Item::STATUS_PENDING
);
$config->save();
