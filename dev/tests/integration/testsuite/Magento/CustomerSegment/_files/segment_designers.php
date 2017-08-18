<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $segment \Magento\CustomerSegment\Model\Segment */
$segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerSegment\Model\Segment::class
);
$segment->loadPost(['name' => 'Designers', 'is_active' => '1']);
$segment->save();
