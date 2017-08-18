<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
// @codingStandardsIgnoreFile

require __DIR__ . '/../../../Magento/Sales/_files/default_rollback.php';
require __DIR__ . '/../../../Magento/Catalog/_files/product_simple.php';

use Magento\TestFramework\Helper\Bootstrap;
use Magento\Sales\Model\Order\Payment;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\Order\ItemRepository;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\OrderRepository;

$objectManager = Bootstrap::getObjectManager();

$payment = $objectManager->create(Payment::class);
$payment->setMethod('checkmo');

/** @var Order $order */
$order = $objectManager->create(Order::class);
$order->setIncrementId('100000001')
    ->setState(Order::STATE_PROCESSING)
    ->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING))
    ->setSubtotal(50)
    ->setGrandTotal(50)
    ->setBaseSubtotal(50)
    ->setBaseGrandTotal(50)
    ->setStoreId($objectManager->get(StoreManagerInterface::class)->getStore()->getId())
    ->setPayment($payment);

/** @var OrderRepository $orderRepository */
$orderRepository = $objectManager->create(OrderRepository::class);
$orderRepository->save($order);

$orderItemData = [
        OrderItemInterface::PRODUCT_ID   => 2,
        OrderItemInterface::BASE_PRICE   => 75,
        OrderItemInterface::ORDER_ID     => $order->getId(),
        OrderItemInterface::QTY_ORDERED  => 2,
        OrderItemInterface::QTY_INVOICED => 2,
        OrderItemInterface::PRICE        => 75,
        OrderItemInterface::BASE_ROW_TOTAL => 150,
        OrderItemInterface::ROW_TOTAL    => 150,
        OrderItemInterface::ROW_INVOICED => 50,
        OrderItemInterface::BASE_ROW_INVOICED => 50,
        OrderItemInterface::PRODUCT_TYPE => 'simple',
];

/** @var $orderItem Item */
$orderItem = $objectManager->create(Item::class);
$orderItem->setData($orderItemData)
    ->setQtyInvoiced(1);

/** @var ItemRepository $itemRepository */
$itemRepository = $objectManager->create(ItemRepository::class);
$itemRepository->save($orderItem);

$order->setBaseCustomerBalanceAmount(100);
$order->setCustomerBalanceAmount(100);
$order->setBaseCustomerBalanceInvoiced(50);
$order->setCustomerBalanceInvoiced(50);
$order->setBaseCustomerBalanceRefunded(0);
$order->setCustomerBalanceRefunded(0);
$order->setBsCustomerBalTotalRefunded(0);
$order->setCustomerBalTotalRefunded(0);

$orderRepository->save($order);
