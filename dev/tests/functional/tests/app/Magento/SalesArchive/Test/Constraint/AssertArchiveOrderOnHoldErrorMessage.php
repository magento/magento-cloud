<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert on hold fail message is displayed on archive order index page
 */
class AssertArchiveOrderOnHoldErrorMessage extends AbstractConstraint
{
    /**
     * Text value to be checked
     */
    const ERROR_MESSAGE = 'No order(s) were put on hold.';

    /**
     * Assert on hold fail message is displayed on archive order index page
     *
     * @param ArchiveOrders $archiveOrders
     * @return void
     */
    public function processAssert(ArchiveOrders $archiveOrders)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::ERROR_MESSAGE,
            $archiveOrders->getMessagesBlock()->getErrorMessage()
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'On hold fail message is displayed on order index page.';
    }
}
