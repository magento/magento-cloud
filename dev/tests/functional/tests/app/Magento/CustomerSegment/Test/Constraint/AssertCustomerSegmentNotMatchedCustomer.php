<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that grid on 'Matched Customer' tab not contains customer according to conditions.
 */
class AssertCustomerSegmentNotMatchedCustomer extends AbstractConstraint
{
    /**
     * Assert that grid on 'Matched Customer' tab not contains customer according to conditions.
     *
     * @param Customer $customer
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerSegmentNew $customerSegmentNew
     * @return void
     */
    public function processAssert(
        Customer $customer,
        CustomerSegment $customerSegment,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerSegmentNew $customerSegmentNew
    ) {
        $customerSegmentIndex->open();
        $formTabs = $customerSegmentNew->getCustomerSegmentForm();
        $customerSegmentIndex->getGrid()->searchAndOpen(['grid_segment_name' => $customerSegment->getName()]);
        $customerSegmentGrid = $formTabs->getMatchedCustomers()->getCustomersGrid();
        $customerSegmentNew->getPageMainActions()->refreshSegmentData();
        $formTabs->openTab('matched_customers');
        \PHPUnit_Framework_Assert::assertFalse(
            $customerSegmentGrid->isRowVisible(['grid_email' => $customer->getEmail()]),
            'Customer is present in grid when it should not.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer is absent in Customer Segment grid. Number of matched customer equals to rows in grid.';
    }
}
