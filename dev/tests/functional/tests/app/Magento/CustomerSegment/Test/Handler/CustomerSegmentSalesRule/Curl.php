<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Handler\CustomerSegmentSalesRule;

/**
 * Curl handler for creating sales rule.
 */
class Curl extends \Magento\SalesRule\Test\Handler\SalesRule\Curl
{
    /**
     * Map of type additional parameter.
     *
     * @var array
     */
    protected $additionalMapTypeParams = [
        'Customer Segment' => [
            'type' => \Magento\CustomerSegment\Model\Segment\Condition\Segment::class,
        ],
    ];
}
