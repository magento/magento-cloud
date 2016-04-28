<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var $product \Magento\Catalog\Model\Product */
$product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Product');
$product->load(1);
$registry = $objectManager->get('Magento\Framework\Registry');
if ($product->getId()) {
    $registry->unregister('isSecureArea');
    $registry->register('isSecureArea', true);
    $product->delete();
    $registry->unregister('isSecureArea');
    $registry->register('isSecureArea', false);
}
