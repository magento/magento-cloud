<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $segment \Magento\CustomerSegment\Model\Segment */
$segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    'Magento\CustomerSegment\Model\Segment'
);
$segment->loadPost(['name' => 'Developers', 'is_active' => '1']);
$segment->save();
