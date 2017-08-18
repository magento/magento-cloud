<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

global $fixtureBaseDir;

include $fixtureBaseDir . '/Magento/Sales/_files/order_list.php';

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

foreach ($orders as $orderData) {
    $order = $objectManager->create(\Magento\Sales\Model\Order::class);
    $order->load($orderData['increment_id'], 'increment_id');

    /** @var $rma \Magento\Rma\Model\Rma */
    $rma = $objectManager->create(\Magento\Rma\Model\Rma::class);
    $rma->setOrderId($order->getId());
    $rma->setIncrementId($orderData['increment_id']);
    $rma->save();
}
