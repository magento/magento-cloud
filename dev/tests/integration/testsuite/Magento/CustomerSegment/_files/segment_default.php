<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved
 * See COPYING.txt for license details.
 */
use Magento\TestFramework\Helper\Bootstrap;

$data = [
    'name' => 'Customer Segment Default',
    'description' => 'Customer Segment Description',
    'website_ids' => [1],
    'is_active' => '1',
];
/** @var $segment \Magento\CustomerSegment\Model\Segment */
$segment = Bootstrap::getObjectManager()->create(
    \Magento\CustomerSegment\Model\Segment::class
);
$segment->loadPost($data);
$segment->save();
