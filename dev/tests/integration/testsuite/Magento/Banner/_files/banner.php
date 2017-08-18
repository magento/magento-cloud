<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Creates banner with enabled status and text content
 */

/** @var $banner \Magento\Banner\Model\Banner */
$banner = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Banner\Model\Banner::class);

$banner->setIsEnabled(
    \Magento\Banner\Model\Banner::STATUS_ENABLED
)->setName(
    'Test Banner'
)->setTypes(
    ''
)->setStoreContents(
    [0 => 'Banner Content']
)->save();
