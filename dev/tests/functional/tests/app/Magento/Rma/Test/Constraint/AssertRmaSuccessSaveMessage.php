<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Constraint;

use Magento\Rma\Test\Page\Adminhtml\RmaIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert success message appears after submitting new return request.
 */
class AssertRmaSuccessSaveMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'middle';
    /* end tags */

    /**
     * Rma success create message.
     */
    const SUCCESS_CREATE_MESSAGE = 'You submitted the RMA request.';

    /**
     * Assert success message appears after submitting new return request.
     *
     * @param RmaIndex $rmaIndex
     * @return void
     */
    public function processAssert(RmaIndex $rmaIndex)
    {
        $pageMessage = $rmaIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_CREATE_MESSAGE,
            $pageMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_CREATE_MESSAGE
            . "\nActual: " . $pageMessage
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Success message appears after submitting new return request.';
    }
}
