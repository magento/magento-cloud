<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/Sales/_files/quote_with_customer.php';
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$productRepository = $objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
$product = $productRepository->get('simple');
$product->setTaxClassId(2)->save();
$configResource = $objectManager->get(\Magento\Config\Model\ResourceModel\Config::class);
$configResource->saveConfig(
    'tax/classes/wrapping_tax_class',
    '2',
    'default',
    0
);
$configResource->saveConfig(
    'tax/cart_display/price',
    '3',
    'default',
    0
);
$configResource->saveConfig(
    'tax/cart_display/subtotal',
    '3',
    'default',
    0
);
$configResource->saveConfig(
    'tax/cart_display/gift_wrapping',
    '3',
    'default',
    0
);
$configResource->saveConfig(
    'tax/cart_display/full_summary',
    '3',
    'default',
    0
);
/** @var \Magento\Framework\App\Config\ReinitableConfigInterface $config */
$config = $objectManager->get(\Magento\Framework\App\Config\ReinitableConfigInterface::class);
$config->reinit();
