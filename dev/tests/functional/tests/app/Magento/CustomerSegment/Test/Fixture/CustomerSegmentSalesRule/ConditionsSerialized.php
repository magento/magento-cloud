<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Fixture\CustomerSegmentSalesRule;

/**
 * Source for conditions serialized.
 */
class ConditionsSerialized extends \Magento\SalesRule\Test\Fixture\SalesRule\ConditionsSerialized
{
    /**
     * Path to additional chooser grid class.
     *
     * @var array
     */
    protected $additionalChooserGrid = [
        'Customer Segment' => [
            'field' => 'name',
            'class' => 'Magento/CustomerSegment/Test/Block/Adminhtml/Customersegment/Grid/Chooser',
        ],
    ];
}
