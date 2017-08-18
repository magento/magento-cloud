<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert release error message is displayed on archived order index page
 */
class AssertArchiveOrderReleaseErrorMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Text value to be checked
     */
    const ERROR_MESSAGE = 'No order(s) were released from on hold status.';

    /**
     * Assert release error message is displayed on archived order index page
     *
     * @param ArchiveOrders $archiveOrder
     * @return void
     */
    public function processAssert(ArchiveOrders $archiveOrder)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::ERROR_MESSAGE,
            $archiveOrder->getMessagesBlock()->getErrorMessage()
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Release error message is displayed on archived order index page.';
    }
}
