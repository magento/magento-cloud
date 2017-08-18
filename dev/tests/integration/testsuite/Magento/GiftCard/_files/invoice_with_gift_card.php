<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
require 'order_with_gift_card.php';

/** @var \Magento\Sales\Model\Order $order */

$orderService = \Magento\TestFramework\ObjectManager::getInstance()->create(
    \Magento\Sales\Api\InvoiceManagementInterface::class
);

$invoice = $orderService->prepareInvoice($order);
$invoice->register();

$order = $invoice->getOrder();
$order->setIsInProcess(true);

$transactionSave = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create(\Magento\Framework\DB\Transaction::class);

$transactionSave->addObject($invoice)->addObject($order)->save();
