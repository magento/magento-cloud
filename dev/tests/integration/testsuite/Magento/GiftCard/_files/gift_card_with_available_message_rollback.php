<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get('Magento\Framework\Registry');
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$product = $objectManager->create('Magento\Catalog\Model\Product');
$product->load($product->getIdBySku('gift-card-with-allowed-messages'));
if ($product->getId()) {
    $product->delete();
}
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
