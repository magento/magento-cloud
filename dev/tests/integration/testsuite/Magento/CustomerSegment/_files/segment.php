<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

$data = ['name' => 'Customer Segment 1', 'website_ids' => [1], 'is_active' => '1'];
/** @var $segment \Magento\CustomerSegment\Model\Segment */
$segment = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerSegment\Model\Segment::class
);
$segment->loadPost($data);
$segment->save();

$segment->matchCustomers();
