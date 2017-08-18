<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerSegmentNotInGrid
 * Assert that created customer segment not presents in grid
 */
class AssertCustomerSegmentNotInGrid extends AbstractConstraint
{
    /**
     * Assert that created customer segment not presents in grid
     *
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @return void
     */
    public function processAssert(CustomerSegment $customerSegment, CustomerSegmentIndex $customerSegmentIndex)
    {
        $customerSegmentIndex->open();
        \PHPUnit_Framework_Assert::assertFalse(
            $customerSegmentIndex->getGrid()->isRowVisible(['grid_segment_name' => $customerSegment->getName()]),
            'Customer Segments is present in grid.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Segments is absent in grid.';
    }
}
