<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderCheckout;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\ObjectManager;

/**
 * Class OnlineRefundTest
 */
class OnlineRefundTest extends AbstractRefundTest
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
     * @ZephirId MAGETWO-12436, MAGETWO-13061, MAGETWO-13062, MAGETWO-13063, MAGETWO-13059
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

        // Step 2: Open Invoice
        $tabsWidget->openTab('invoices');
        $orderPage->getInvoicesGrid()->clickInvoiceAmount();

        // Step 3: Click "Credit Memo" button on the Invoice Page
        $orderPage->getOrderActionsBlock()->orderInvoiceCreditMemo();

        // Step 4: Submit Credit Memo
        Factory::getPageFactory()->getSalesOrderCreditmemoNew()->getActionsBlock()->refund();

        $orderPage = Factory::getPageFactory()->getSalesOrder();
        $tabsWidget = $orderPage->getFormTabsBlock();

        $this->assertContains(
            'You created the credit memo.',
            $orderPage->getMessagesBlock()->getSuccessMessages()
        );

        // Step 5: Go to "Credit Memos" tab
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

        // Step 6: Go to Transactions tab
        $tabsWidget->openTab('transactions');
        $this->assertContains(
            $orderPage->getTransactionsGrid()->getTransactionType(),
            'Refund'
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
            [Factory::getFixtureFactory()->getMagentoSalesPaypalExpressOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPayflowProOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPaymentsProOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPaymentsAdvancedOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPayflowLinkOrder()]
        ];
    }

    /**
     * Delete all tax rules after test.
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        ObjectManager::getInstance()->create('Magento\Tax\Test\TestStep\DeleteAllTaxRulesStep')->run();
    }
}
