<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerCustomAttributeNotInCustomerSegment
 * Assert that deleted customer attribute is not available during creation of customer segments
 */
class AssertCustomerCustomAttributeNotInCustomerSegment extends AbstractConstraint
{
    /**
     * Assert that deleted customer attribute is not available during creation of customer segments
     *
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentNew $customerSegmentNew
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerCustomAttribute $customerAttribute
     * @return void
     */
    public function processAssert(
        CustomerSegment $customerSegment,
        CustomerSegmentNew $customerSegmentNew,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerCustomAttribute $customerAttribute
    ) {
        $customerSegmentIndex->open();
        $customerSegmentIndex->getPageActionsBlock()->addNew();
        $customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $customerSegmentNew->getPageMainActions()->saveAndContinue();
        $customerSegmentNew->getCustomerSegmentForm()->openTab('conditions');
        \PHPUnit_Framework_Assert::assertFalse(
            $customerSegmentNew->getCustomerSegmentForm()->isAttributeInConditions($customerAttribute),
            'Customer Custom Attribute with attribute code: \'' . $customerAttribute->getAttributeCode() . '\' '
            . 'is present during creation of customer segments.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute is not available during creation of customer segments.';
    }
}
