<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$product = $objectManager->create(\Magento\Catalog\Model\Product::class);
$product->load($product->getIdBySku('gift-card-with-open-amount'));
if ($product->getId()) {
    $product->delete();
}
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
