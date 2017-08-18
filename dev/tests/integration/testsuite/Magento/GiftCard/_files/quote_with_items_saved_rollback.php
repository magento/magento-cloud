<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$quote = $objectManager->create(\Magento\Quote\Model\Quote::class);
$quote->load('test_order_item_with_gift_card_items', 'reserved_order_id');
if ($quote->getId()) {
    $quote->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);

require __DIR__ . '/../../Customer/_files/customer_address_rollback.php';
require __DIR__ . '/../../Customer/_files/customer_rollback.php';
require __DIR__ . '/../../GiftCard/_files/gift_card_with_available_message_rollback.php';
