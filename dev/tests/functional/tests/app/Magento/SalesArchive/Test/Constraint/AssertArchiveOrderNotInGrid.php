<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that order is absent in archive orders grid
 */
class AssertArchiveOrderNotInGrid extends AbstractConstraint
{
    /**
     * Assert that order is absent in archive orders grid
     *
     * @param OrderInjectable $order
     * @param ArchiveOrders $archiveOrders
     * @param string $orderStatus
     * @return void
     */
    public function processAssert(OrderInjectable $order, ArchiveOrders $archiveOrders, $orderStatus)
    {
        $data = $order->getData();
        $filter = [
            'id' => $data['id'],
            'status' => $orderStatus,
        ];
        $archiveOrders->open();
        $errorMessage = implode(', ', $filter);
        \PHPUnit_Framework_Assert::assertFalse(
            $archiveOrders->getSalesArchiveOrderGrid()->isRowVisible($filter),
            'Order with following data \'' . $errorMessage . '\' is present in archive orders grid.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Order is absent in archive orders grid.';
    }
}
