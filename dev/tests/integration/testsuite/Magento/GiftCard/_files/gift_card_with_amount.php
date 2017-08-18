<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $product \Magento\Catalog\Model\Product */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$product = $objectManager->create(\Magento\Catalog\Model\Product::class);

$amountData1 = [
    'value' => 7,
    'website_id' => 0,
    'attribute_id' => 132
];
$amountData2 = [
    'value' => 17,
    'website_id' => 0,
    'attribute_id' => 132
];

$extensionAttributes = $objectManager->create(\Magento\Catalog\Api\Data\ProductExtension::class);
$giftCardAmountFactory = $objectManager->create(\Magento\GiftCard\Api\Data\GiftcardAmountInterfaceFactory::class);
$amount1 = $giftCardAmountFactory->create(['data' => $amountData1]);
$amount2 = $giftCardAmountFactory->create(['data' => $amountData2]);
$extensionAttributes->setGiftcardAmounts([$amount1, $amount2]);

$product->setTypeId(\Magento\GiftCard\Model\Catalog\Product\Type\Giftcard::TYPE_GIFTCARD)
    ->setAttributeSetId(4)
    ->setWebsiteIds([1])
    ->setName('Simple Gift Card')
    ->setSku('gift-card-with-amount')
    ->setDescription('Gift Card Description')
    ->setMetaTitle('Gift Card Meta Title')
    ->setMetaKeyword('Gift Card Meta Keyword')
    ->setMetaDescription('Gift Card Meta Description')
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setCategoryIds([2])
    ->setStockData(['use_config_manage_stock' => 0])
    ->setCanSaveCustomOptions(true)
    ->setHasOptions(true)
    ->setAllowOpenAmount(0)
    ->setExtensionAttributes($extensionAttributes)
    ->save();
