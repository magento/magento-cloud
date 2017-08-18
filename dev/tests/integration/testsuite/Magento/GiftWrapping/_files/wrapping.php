<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var \Magento\GiftWrapping\Model\Wrapping $wrapping */
$wrapping = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\GiftWrapping\Model\Wrapping::class
);
$wrapping->setDesign('Test Wrapping')
    ->setStatus(1)
    ->setBasePrice(5.00)
    ->setImage('image.png')
    ->setStoreId(\Magento\Store\Model\Store::DEFAULT_STORE_ID)
    ->save();
