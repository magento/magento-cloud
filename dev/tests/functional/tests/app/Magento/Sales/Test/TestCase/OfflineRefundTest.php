<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\TestCase;

use Magento\Sales\Test\Fixture\AuthorizeNetOrder;
use Magento\Sales\Test\Fixture\OrderCheckout;
use Magento\Mtf\Factory\Factory;

/**
 * Class OfflineRefundTest
 */
class OfflineRefundTest extends AbstractRefundTest
{
    /* tags */
    const TEST_TYPE = '3rd_party_test_deprecated';
    /* end tags */

    /**
     * Tests providing refunds.
     *
     * @param OrderCheckout $fixture
     *
     * @return void
     *
     * @dataProvider dataProviderOrder
     * @ZephirId MAGETWO-13058, MAGETWO-19985
     */
    public function testRefund(OrderCheckout $fixture)
    {
        // Setup preconditions
        parent::setupPreconditions($fixture);

        $orderId = $fixture->getOrderId();

        // Step 1: Order View Page
        $orderPage = Factory::getPageFactory()->getSalesOrder();
        $orderPage->open();
        $orderPage->getOrderGridBlock()->searchAndOpen(['id' => $orderId]);

        $tabsWidget = $orderPage->getFormTabsBlock();

        if ($fixture instanceof AuthorizeNetOrder) {
            // Step 2: Open Invoice
            $tabsWidget->openTab('invoices');
            $orderPage->getInvoicesGrid()->clickInvoiceAmount();

            // Step 3: Click "Credit Memo" button on the Invoice Page
            $orderPage->getOrderActionsBlock()->orderInvoiceCreditMemo();
        } else {
            // Step 2: Click "Credit Memo" button on the Order Page
            $orderPage->getOrderActionsBlock()->orderCreditMemo();
        }

        // Step 3/4: Submit Credit Memo
        Factory::getPageFactory()->getSalesOrderCreditmemoNew()->getActionsBlock()->refundOffline();

        $orderPage = Factory::getPageFactory()->getSalesOrder();
        $tabsWidget = $orderPage->getFormTabsBlock();

        $this->assertContains(
            'You created the credit memo.',
            $orderPage->getMessagesBlock()->getSuccessMessages()
        );

        // Step 4/5: Go to "Credit Memos" tab
        $tabsWidget->openTab('creditmemos');
        $this->assertContains(
            $fixture->getGrandTotal(),
            $orderPage->getCreditMemosGrid()->getRefundAmount(),
            'Incorrect refund amount for the order #' . $orderId
        );
        $this->assertContains(
            $orderPage->getCreditMemosGrid()->getStatus(),
            'Refunded'
        );
    }

    /**
     * Data providers for creating, closing and refunding an order
     *
     * @return array
     */
    public function dataProviderOrder()
    {
        return [
            [Factory::getFixtureFactory()->getMagentoSalesAuthorizeNetOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalStandardOrder()]
        ];
    }
}
