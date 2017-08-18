<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Constraint;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\Adminhtml\SalesOrderView;
use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that status is correct on order page in admin panel.
 */
class AssertArchiveOrderStatusInGrid extends AbstractConstraint
{
    /**
     * Assert that status is correct on order page in admin panel (same with value of orderStatus variable).
     *
     * @param OrderInjectable $order
     * @param ArchiveOrders $archiveOrders
     * @param SalesOrderView $salesOrderView
     * @param string $orderStatus
     * @return void
     */
    public function processAssert(
        OrderInjectable $order,
        ArchiveOrders $archiveOrders,
        SalesOrderView $salesOrderView,
        $orderStatus
    ) {
        $filter = [
            'id' => $order->getId(),
            'status' => $orderStatus,
        ];
        $archiveOrders->open();
        $archiveOrders->getSalesArchiveOrderGrid()->searchAndOpen($filter);
        /** @var \Magento\Sales\Test\Block\Adminhtml\Order\View\Tab\Info $infoTab */
        $infoTab = $salesOrderView->getOrderForm()->openTab('info')->getTab('info');
        $actualOrderStatus = $infoTab->getOrderStatus();
        \PHPUnit_Framework_Assert::assertEquals(
            $orderStatus,
            $actualOrderStatus,
            "Order status is not correct on archived order's page."
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return "Order status is correct on archived order's page.";
    }
}
