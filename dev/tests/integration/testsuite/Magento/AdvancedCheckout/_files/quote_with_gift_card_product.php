<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/GiftCard/_files/gift_card.php';

/** @var \Magento\Catalog\Api\ProductRepositoryInterface $productRepository */
$productRepository = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
    ->create('Magento\Catalog\Api\ProductRepositoryInterface');
/** @var $product \Magento\Catalog\Model\Product */
$product = $productRepository->get('gift-card');

$requestInfo = new \Magento\Framework\DataObject(
    [
        'qty' => 1,
        'giftcard_amount' => 'custom',
        'custom_giftcard_amount' => 200,
        'giftcard_sender_name' => 'Sender',
        'giftcard_sender_email' => 'aerfg@sergserg.com',
        'giftcard_recipient_name' => 'Registrant',
        'giftcard_recipient_email' => 'awefaef@dsrthb.com',
        'giftcard_message' => 'message',
    ]
);

/** @var $cart \Magento\Checkout\Model\Cart */
$cart = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Checkout\Model\Cart');
$cart->addProduct($product, $requestInfo);
$cart->save();

/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$objectManager->get('Magento\Framework\Registry')->unregister('_singleton/Magento\Checkout\Model\Session');

/** @var $objectManager \Magento\TestFramework\ObjectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$objectManager->removeSharedInstance('Magento\Checkout\Model\Session');
