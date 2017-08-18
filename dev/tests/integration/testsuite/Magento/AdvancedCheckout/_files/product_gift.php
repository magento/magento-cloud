<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $product \Magento\Catalog\Model\Product */
$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->setTypeId(
    \Magento\GiftCard\Model\Catalog\Product\Type\Giftcard::TYPE_GIFTCARD
)->setId(
    1
)->setAttributeSetId(
    4
)->setWebsiteIds(
    [1]
)->setName(
    'GiftCard Product'
)->setSku(
    'gift1'
)->setPrice(
    10
)->setDescription(
    'Description with <b>html tag</b>'
)->setMetaTitle(
    'gift meta title'
)->setMetaKeyword(
    'gift meta keyword'
)->setMetaDescription(
    'gift meta description'
)->setVisibility(
    \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
)->setStatus(
    \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
)->setCategoryIds(
    [2]
)->setStockData(
    ['use_config_manage_stock' => 0]
)->setCanSaveCustomOptions(
    true
)->setHasOptions(
    true
)->setAllowOpenAmount(
    1
)->save();

/** @var $product \Magento\Catalog\Model\Product */
$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Catalog\Model\Product::class);
$product->load(1);

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

require __DIR__ . '/../../../Magento/Checkout/_files/cart.php';
