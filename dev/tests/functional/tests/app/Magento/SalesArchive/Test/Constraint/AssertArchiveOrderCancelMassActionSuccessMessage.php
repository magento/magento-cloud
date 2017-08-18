<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success message is displayed on "Archived Orders Grid" page
 */
class AssertArchiveOrderCancelMassActionSuccessMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Message displayed after cancel order from archive
     */
    const SUCCESS_MESSAGE = 'We canceled %d order(s).';

    /**
     * Assert that success message is displayed on "Archived Orders Grid" page
     *
     * @param ArchiveOrders $archiveOrders
     * @param int $successMassActions
     * @return void
     */
    public function processAssert(ArchiveOrders $archiveOrders, $successMassActions)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, $successMassActions),
            $archiveOrders->getMessagesBlock()->getSuccessMessage(),
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
        return 'Success message of canceled orders is present on archived orders grid.';
    }
}
