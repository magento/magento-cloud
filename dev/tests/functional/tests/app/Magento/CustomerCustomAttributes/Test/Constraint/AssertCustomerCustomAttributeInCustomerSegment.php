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
 * Class AssertCustomerCustomAttributeInCustomerSegment
 * Assert that created customer attribute is available during creation of customer segments
 */
class AssertCustomerCustomAttributeInCustomerSegment extends AbstractConstraint
{
    /**
     * Assert that created customer attribute is available during creation of customer segments
     *
     * @param CustomerSegment $customerSegment
     * @param CustomerSegmentNew $customerSegmentNew
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @param CustomerCustomAttribute $customerAttribute
     * @param CustomerCustomAttribute $initialCustomerAttribute
     * @return void
     */
    public function processAssert(
        CustomerSegment $customerSegment,
        CustomerSegmentNew $customerSegmentNew,
        CustomerSegmentIndex $customerSegmentIndex,
        CustomerCustomAttribute $customerAttribute,
        CustomerCustomAttribute $initialCustomerAttribute = null
    ) {
        $customerAttribute = $initialCustomerAttribute === null ? $customerAttribute : $initialCustomerAttribute;
        $customerSegmentIndex->open();
        $customerSegmentIndex->getPageActionsBlock()->addNew();
        $customerSegmentNew->getCustomerSegmentForm()->fill($customerSegment);
        $customerSegmentNew->getPageMainActions()->saveAndContinue();
        $customerSegmentNew->getCustomerSegmentForm()->openTab('conditions');
        \PHPUnit_Framework_Assert::assertTrue(
            $customerSegmentNew->getCustomerSegmentForm()->isAttributeInConditions($customerAttribute),
            'Customer Custom Attribute with attribute code: \'' . $customerAttribute->getAttributeCode() . '\' '
            . 'is absent during creation of customer segments.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute is available during creation of customer segments.';
    }
}
