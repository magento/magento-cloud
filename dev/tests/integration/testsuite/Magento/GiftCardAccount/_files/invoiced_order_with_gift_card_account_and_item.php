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
    ->setSubtotal(100)
    ->setGrandTotal(100)
    ->setBaseSubtotal(100)
    ->setBaseGrandTotal(100)
    ->setStoreId($objectManager->get(StoreManagerInterface::class)->getStore()->getId())
    ->setPayment($payment);

/** @var OrderRepository $orderRepository */
$orderRepository = $objectManager->create(OrderRepository::class);
$orderRepository->save($order);

$orderItemData = [
        OrderItemInterface::PRODUCT_ID   => 2,
        OrderItemInterface::BASE_PRICE   => 100,
        OrderItemInterface::ORDER_ID     => $order->getId(),
        OrderItemInterface::QTY_ORDERED  => 2,
        OrderItemInterface::QTY_INVOICED => 2,
        OrderItemInterface::PRICE        => 100,
        OrderItemInterface::BASE_ROW_TOTAL => 100,
        OrderItemInterface::ROW_TOTAL    => 100,
        OrderItemInterface::ROW_INVOICED => 100,
        OrderItemInterface::BASE_ROW_INVOICED => 100,
        OrderItemInterface::PRODUCT_TYPE => 'simple',
];

/** @var $orderItem Item */
$orderItem = $objectManager->create(Item::class);
$orderItem->setData($orderItemData)
    ->setQtyInvoiced(1);

/** @var ItemRepository $itemRepository */
$itemRepository = $objectManager->create(ItemRepository::class);
$itemRepository->save($orderItem);

$order->setBaseGiftCardsAmount(50);
$order->setGiftCardsAmount(50);
$order->setBaseGiftCardsInvoiced(100);
$order->setGiftCardsInvoiced(100);
$order->setBaseGiftCardsRefunded(50);
$order->setGiftCardsRefunded(50);

$orderRepository->save($order);
