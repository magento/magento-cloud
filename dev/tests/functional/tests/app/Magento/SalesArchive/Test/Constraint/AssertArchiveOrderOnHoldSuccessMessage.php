<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert on hold success message is displayed on archived order index page
 */
class AssertArchiveOrderOnHoldSuccessMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const SUCCESS_MESSAGE = 'You have put %d order(s) on hold.';

    /**
     * Assert on hold success message is displayed on archived order index page
     *
     * @param ArchiveOrders $archiveOrder
     * @param int $successMassActions
     * @return void
     */
    public function processAssert(ArchiveOrders $archiveOrder, $successMassActions)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, $successMassActions),
            $archiveOrder->getMessagesBlock()->getSuccessMessage()
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'On hold success message is displayed on archived order index page.';
    }
}
