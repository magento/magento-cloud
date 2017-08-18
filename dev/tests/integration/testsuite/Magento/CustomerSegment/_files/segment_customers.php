<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require __DIR__ . '/../../../Magento/Customer/_files/three_customers.php';

/** @var $segmentFactory \Magento\CustomerSegment\Model\SegmentFactory */
$segmentFactory = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
    \Magento\CustomerSegment\Model\SegmentFactory::class
);

// Create Segments
$total = 1;
for ($i = 0; $i < 3; $i++) {
    $ruleName = sprintf('Customer Segment %1$d', $i);

    $data = [
        'name'          => $ruleName,
        'website_ids'   => [1],
        'is_active'     => '1',
        'apply_to'      => 0,
    ];

    $segment = $segmentFactory->create();
    $segment->loadPost($data);
    $segment->save();
    $segmentId = $segment->getSegmentId();

    // Add Conditions
    if ($i == 1) {
        $value = sprintf('customer@search.example.com');
    } else {
        $value = sprintf('customer%1$d@search.example.com', $i);
    }

    $conditions = [
        1 => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Combine\Root::class,
            'aggregator' => 'any',
            'value' => '1',
            'new_child' => '',
        ],
        '1--1' => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Customer\Attributes::class,
            'attribute' => 'email',
            'operator' => '==',
            'value' => $value,
        ]
    ];

    $data = [
        'name'          => $ruleName,
        'segment_id'    => $segmentId,
        'website_ids'   => [1],
        'is_active'     => '1',
        'conditions'    => $conditions
    ];

    $segment->loadPost($data);
    $segment->save();

    if ($segment->getApplyTo() != \Magento\CustomerSegment\Model\Segment::APPLY_TO_VISITORS) {
        $segment->matchCustomers();
    }
}
