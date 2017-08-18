<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success message is displayed on "Orders Grid" page
 */
class AssertArchiveOrderSuccessMessage extends AbstractConstraint
{
    /**
     * Message displayed after moving order to archive
     */
    const SUCCESS_MESSAGE = 'We archived %d order(s).';

    /**
     * Assert that success message is displayed on "Orders Grid" page
     *
     * @param OrderIndex $orderIndex
     * @param string $number
     * @return void
     */
    public function processAssert(OrderIndex $orderIndex, $number)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, $number),
            $orderIndex->getMessagesBlock()->getSuccessMessage(),
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
        return 'Order success move to archive message is present.';
    }
}
