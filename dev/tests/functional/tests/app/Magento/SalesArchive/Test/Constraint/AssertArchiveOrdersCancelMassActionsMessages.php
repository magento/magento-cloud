<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that error and success messages are displayed on "Archived Orders Grid" page
 */
class AssertArchiveOrdersCancelMassActionsMessages extends AbstractConstraint
{
    /**
     * Message displayed after cancel order from archive
     */
    const SUCCESS_MESSAGE = 'We canceled %d order(s).';

    /**
     * Message displayed after unsuccessful orders canceling
     */
    const ERROR_MESSAGE = '%d order(s) cannot be canceled.';

    /**
     * Assert that error and success messages are displayed on "Archived Orders Grid" page
     *
     * @param ArchiveOrders $archiveOrders
     * @param int $successMassActions
     * @param int $ordersQty
     * @return void
     */
    public function processAssert(ArchiveOrders $archiveOrders, $successMassActions, $ordersQty)
    {
        $expectedMessages = [
            sprintf(self::SUCCESS_MESSAGE, $successMassActions),
            sprintf(self::ERROR_MESSAGE, $ordersQty - $successMassActions),
        ];
        $actualMessages = [
            $archiveOrders->getMessagesBlock()->getSuccessMessage(),
            $archiveOrders->getMessagesBlock()->getErrorMessage(),
        ];
        \PHPUnit_Framework_Assert::assertEquals(
            $expectedMessages,
            $actualMessages,
            'Wrong messages are displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Success and error messages are present on archived orders grid.';
    }
}
