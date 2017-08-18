<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerSegmentSuccessSaveMessage
 * Assert that success message is displayed after Customer Segments saved
 */
class AssertCustomerSegmentSuccessSaveMessage extends AbstractConstraint
{
    const SUCCESS_MESSAGE = 'You saved the segment.';

    /**
     * Assert that success message is displayed after Customer Segments saved
     *
     * @param CustomerSegmentIndex $customerSegmentIndex
     * @return void
     */
    public function processAssert(CustomerSegmentIndex $customerSegmentIndex)
    {
        $actualMessage = $customerSegmentIndex->getMessagesBlock()->getSuccessMessage();

        \PHPUnit_Framework_Assert::assertContains(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Assert that success message is displayed';
    }
}
