<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerCustomAttributeSuccessDeleteMessage
 * Assert that after delete customer attribute successful message appears
 */
class AssertCustomerCustomAttributeSuccessDeleteMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Text of delete success message
     */
    const SUCCESS_DELETE_MESSAGE = 'You deleted the customer attribute.';

    /**
     * Assert that after delete customer attribute successful message appears
     *
     * @param CustomerAttributeIndex $customerAttributeIndex
     * @return void
     */
    public function processAssert(CustomerAttributeIndex $customerAttributeIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_DELETE_MESSAGE,
            $customerAttributeIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute success delete message is present.';
    }
}
