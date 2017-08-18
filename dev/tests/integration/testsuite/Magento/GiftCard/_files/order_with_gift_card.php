<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

/** @var $billingAddress \Magento\Sales\Model\Order\Address */
$billingAddress = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\Sales\Model\Order\Address::class,
    [
        'data' => [
            'firstname' => 'guest',
            'lastname' => 'guest',
            'email' => 'customer@example.com',
            'street' => 'street',
            'city' => 'Los Angeles',
            'region' => 'CA',
            'postcode' => '1',
            'country_id' => 'US',
            'telephone' => '1',
        ]
    ]
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
    \Magento\GiftCard\Model\Catalog\Product\Type\Giftcard::TYPE_GIFTCARD
)->setBasePrice(
    100
)->setQtyOrdered(
    1
)->setProductOptions(
    [
        'giftcard_amount' => 'custom',
        'custom_giftcard_amount' => 100,
        'giftcard_sender_name' => 'Gift Card Sender Name',
        'giftcard_sender_email' => 'sender@example.com',
        'giftcard_recipient_name' => 'Gift Card Recipient Name',
        'giftcard_recipient_email' => 'recipient@example.com',
        'giftcard_message' => 'Gift Card Message',
        'giftcard_email_template' => 'giftcard_email_template',
    ]
);

/** @var $order \Magento\Sales\Model\Order */
$order = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Sales\Model\Order::class);
$order->setCustomerEmail('mail@to.co')
    ->addItem(
    $orderItem
)->setCustomerEmail(
    'someone@example.com'
)->setIncrementId(
    '100000001'
)->setCustomerIsGuest(
    true
)->setStoreId(
    1
)->setEmailSent(
    1
)->setBillingAddress(
    $billingAddress
)->setShippingAddress(
    $shippingAddress
)->setPayment(
    $payment
);
$order->save();

\Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    \Magento\Framework\App\Config\MutableScopeConfigInterface::class
)->setValue(
    \Magento\GiftCardAccount\Model\Pool::XML_CONFIG_POOL_SIZE,
    1,
    'website',
    'base'
);
/** @var $pool \Magento\GiftCardAccount\Model\Pool */
$pool = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\GiftCardAccount\Model\Pool::class);
$pool->setWebsiteId(1)->generatePool();
