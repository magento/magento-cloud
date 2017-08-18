<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
$website = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Store\Model\Website::class);
if (!$website->load('wrapping_website')->getId()) {
    $website->setName('Wrapping Website')->setCode('wrapping_website')->save();
}

/** @var Magento\GiftWrapping\Model\Wrapping $wrapping */
$wrapping = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\GiftWrapping\Model\Wrapping::class
);
$wrapping->setDesign('Test Wrapping 1')
    ->setStatus(1)
    ->setWebsiteIds([1])
    ->setBasePrice(5.00)
    ->setImage('image1.png')
    ->save();

/** @var \Magento\GiftWrapping\Model\Wrapping $wrapping */
$wrapping = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\GiftWrapping\Model\Wrapping::class
);
$wrapping->setDesign('Test Wrapping 2')
    ->setStatus(1)
    ->setStoreId(1)
    ->setDesign('Test Wrapping 2 design')
    ->setWebsiteIds([1, $website->getId()])
    ->setBasePrice(10.00)
    ->setImage('image2.png')
    ->save();

/** @var \Magento\GiftWrapping\Model\Wrapping $wrapping */
$wrapping = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\GiftWrapping\Model\Wrapping::class
);
$wrapping->setDesign('Test Wrapping 3')
    ->setStatus(1)
    ->setWebsiteIds([1])
    ->setBasePrice(15.00)
    ->setImage('image3.png')
    ->save();

$wrapping = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\GiftWrapping\Model\Wrapping::class
);
$wrapping->setDesign('Test Wrapping 4')
    ->setStatus(0)
    ->setWebsiteIds([1])
    ->setBasePrice(20.00)
    ->setImage('image4.png')
    ->save();
