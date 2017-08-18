<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that archived orders with fixture data is in the Grid
 */
class AssertArchiveOrdersInGrid extends AbstractConstraint
{
    /**
     * Assert that orders with specified id and status is in archive orders grid
     *
     * @param ArchiveOrders $archiveOrders
     * @param AssertArchiveOrderInGrid $assert
     * @param array $orders
     * @param string $orderStatuses
     * @return void
     */
    public function processAssert(
        ArchiveOrders $archiveOrders,
        AssertArchiveOrderInGrid $assert,
        array $orders,
        $orderStatuses
    ) {
        foreach ($orders as $key => $order) {
            $assert->processAssert($order, $archiveOrders, trim($orderStatuses[$key]));
        }
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'All orders are present in archived sales orders grid.';
    }
}
