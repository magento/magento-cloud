<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that warning message present on order grid page
 */
class AssertArchiveOrderWarningMessage extends AbstractConstraint
{
    /**
     * Message displayed after cancel sales order
     */
    const WARNING_MESSAGE = "We can't archive the selected order(s).";

    /**
     * Assert that warning message present on order grid page
     *
     * @param OrderIndex $orderIndex
     * @return void
     */
    public function processAssert(OrderIndex $orderIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::WARNING_MESSAGE),
            $orderIndex->getMessagesBlock()->getWarningMessage(),
            'Wrong warning message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Warning message that order can\'t be archived is present.';
    }
}
