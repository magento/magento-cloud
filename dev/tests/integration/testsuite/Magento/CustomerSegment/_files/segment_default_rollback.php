<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

/** @var \Magento\Framework\Registry $registry */
$registry = Bootstrap::getObjectManager()->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var Magento\CustomerSegment\Model\Segment $segment */
$segment = Bootstrap::getObjectManager()->create(
    Magento\CustomerSegment\Model\Segment::class
);

$segment->load('Customer Segment Default', 'name');

if (!$segment->isObjectNew()) {
    try {
        $segment->delete();
    } catch (\Exception $e) {
        // Something went wrong.
    }
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
