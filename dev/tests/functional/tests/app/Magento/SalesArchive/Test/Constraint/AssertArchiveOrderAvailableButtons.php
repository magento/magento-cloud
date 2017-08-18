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
 * Assert that specified in data set buttons exist on archived order page in backend
 */
class AssertArchiveOrderAvailableButtons extends AbstractConstraint
{
    /**
     * Assert that specified in data set buttons exist on order page in backend
     *
     * @param OrderInjectable $order
     * @param ArchiveOrders $archiveOrders
     * @param SalesOrderView $salesOrderView
     * @param string $orderButtonsAvailable
     * @param string $orderStatus
     * @return void
     */
    public function processAssert(
        OrderInjectable $order,
        ArchiveOrders $archiveOrders,
        SalesOrderView $salesOrderView,
        $orderButtonsAvailable,
        $orderStatus
    ) {
        $filter = [
            'id' => $order->getId(),
            'status' => $orderStatus,
        ];
        $archiveOrders->open();
        $archiveOrders->getSalesArchiveOrderGrid()->searchAndOpen($filter);
        $actionsBlock = $salesOrderView->getPageActions();

        $buttons = explode(',', $orderButtonsAvailable);
        $absentButtons = [];

        foreach ($buttons as $button) {
            $button = trim($button);
            if (!$actionsBlock->isActionButtonVisible($button)) {
                $absentButtons[] = $button;
            }
        }
        \PHPUnit_Framework_Assert::assertEmpty(
            $absentButtons,
            "Next buttons were not found on page: \n" . implode(";\n", $absentButtons)
        );
    }

    /**
     * Returns string representation of successful assertion
     *
     * @return string
     */
    public function toString()
    {
        return "All buttons are available on order page.";
    }
}
