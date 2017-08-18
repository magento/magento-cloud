<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$addressData = include __DIR__ . '/../../../Magento/Sales/_files/address_data.php';
/** @var $billingAddress \Magento\Sales\Model\Order\Address */
$billingAddress = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Sales\Model\Order\Address::class,
    ['data' => $addressData]
);
$billingAddress->setAddressType('billing');

$shippingAddress = clone $billingAddress;
$shippingAddress->setId(null)->setAddressType('shipping');

/** @var $payment \Magento\Sales\Model\Order\Payment */
$payment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Sales\Model\Order\Payment::class
);
$payment->setMethod('checkmo');

/** @var $orderItem \Magento\Sales\Model\Order\Item */
$orderItem = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Sales\Model\Order\Item::class
);
$orderItem->setProductId(
    1
)->setProductType(
    \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
)->setName(
    'product name'
)->setSku(
    'smp00001'
)->setBasePrice(
    100
)->setQtyOrdered(
    1
)->setQtyShipped(
    1
)->setIsQtyDecimal(
    true
);

/** @var $order \Magento\Sales\Model\Order */
$order = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Sales\Model\Order::class);
$order->addItem(
    $orderItem
)->setIncrementId(
    '100000001'
)->setSubtotal(
    100
)->setBaseSubtotal(
    100
)->setCustomerIsGuest(
    true
)->setCustomerEmail(
    'admin@example.com'
)->setBillingAddress(
    $billingAddress
)->setShippingAddress(
    $shippingAddress
)->setStoreId(
    \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        \Magento\Store\Model\StoreManagerInterface::class
    )->getStore()->getId()
)->setPayment(
    $payment
);
$order->save();
