<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that after customer address attribute is saved successful message appears.
 */
class AssertCustomerAddressAttributeSuccessSaveMessage extends AbstractConstraint
{
    /**
     * Text of save success message
     */
    const SUCCESS_SAVE_MESSAGE = 'You saved the customer address attribute.';

    /**
     * Assert that after customer address attribute is saved successful message appears.
     *
     * @param CustomerAddressAttributeIndex $customerAddressAttributeIndex
     * @return void
     */
    public function processAssert(CustomerAddressAttributeIndex $customerAddressAttributeIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_SAVE_MESSAGE,
            $customerAddressAttributeIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Address Attribute success create message is present.';
    }
}
