<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderCheckout;
use Magento\Sales\Test\Fixture\PaypalStandardOrder;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\TestCase\Functional;

/**
 * Class CloseOrderTest
 *
 */
class CloseOrderTest extends Functional
{
    /* tags */
    const TEST_TYPE = '3rd_party_test_deprecated';
    /* end tags */

    /**
     * Test the closing of sales order for various payment methods.
     *
     * @param OrderCheckout $fixture
     * @dataProvider dataProviderOrder
     *
     * @ZephyrId MAGETWO-12434, MAGETWO-12833, MAGETWO-13014, MAGETWO-13015, MAGETWO-13019, MAGETWO-13020, MAGETWO-13018
     */
    public function testCloseOrder(OrderCheckout $fixture)
    {
        $fixture->persist();

        //Data
        $orderId = $fixture->getOrderId();
        $grandTotal = $fixture->getGrandTotal();

        //Pages
        $pageFactory = Factory::getPageFactory();
        $orderPage = $pageFactory->getSalesOrder();
        $newInvoicePage = $pageFactory->getSalesOrderInvoiceNew();
        $newShipmentPage = $pageFactory->getSalesOrderShipmentNew();

        //Steps
        Factory::getApp()->magentoBackendLoginUser();
        $orderPage->open();
        $orderPage->getOrderGridBlock()->searchAndOpen(['id' => $orderId]);
        $this->assertContains(
            $grandTotal,
            Factory::getPageFactory()->getSalesOrderView()->getOrderTotalsBlock()->getGrandTotal(),
            'Incorrect grand total value for the order #' . $orderId
        );

        /** @var \Magento\Sales\Test\Block\Adminhtml\Order\History $orderHistoryBlock */
        $orderHistoryBlock = Factory::getPageFactory()->getSalesOrderView()->getOrderHistoryBlock();

        if (!($fixture instanceof PaypalStandardOrder)) {
            $orderPage->getOrderActionsBlock()->invoice();
            $newInvoicePage->getTotalsBlock()->setCaptureOption('Capture Online');
            $newInvoicePage->getTotalsBlock()->submit();
            $this->assertContains(
                'The invoice has been created.',
                $orderPage->getMessagesBlock()->getSuccessMessages(),
                'No success message on invoice creation'
            );

            $this->assertContains(
                $grandTotal,
                $orderHistoryBlock->getCommentsHistory(),
                'Incorrect captured amount value for the order #' . $orderId
            );
        } else {
            $this->assertContains(
                $grandTotal,
                $orderHistoryBlock->getCapturedAmount(),
                'Incorrect captured amount value for the order #' . $orderId
            );
        }

        $orderPage->getOrderActionsBlock()->ship();
        $newShipmentPage->getTotalsBlock()->submit();
        $this->assertContains(
            'The shipment has been created.',
            $orderPage->getMessagesBlock()->getSuccessMessages(),
            'No success message on shipment creation'
        );
        $tabsWidget = $orderPage->getFormTabsBlock();

        //Verification on invoice tab
        $tabsWidget->openTab('invoices');
        $this->assertContains(
            $orderPage->getInvoicesGrid()->getInvoiceAmount(),
            $grandTotal
        );

        //Verification on transaction tab
        $tabsWidget->openTab('transactions');
        $this->assertContains(
            $orderPage->getTransactionsGrid()->getTransactionType(),
            'Capture'
        );
        //Verification on order grid
        $orderPage->open();
        $this->assertTrue(
            $orderPage->getOrderGridBlock()->isRowVisible(['id' => $orderId, 'status' => 'Complete']),
            "Order # $orderId in complete state was not found on the grid!"
        );
    }

    /**
     * Data providers for creating an order
     *
     * @return array
     */
    public function dataProviderOrder()
    {
        return [
            [Factory::getFixtureFactory()->getMagentoSalesPaypalExpressOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesAuthorizeNetOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPaymentsProOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPaymentsAdvancedOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPayflowProOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalStandardOrder()],
            [Factory::getFixtureFactory()->getMagentoSalesPaypalPayflowLinkOrder()]
        ];
    }

    /**
     * Delete all tax rules after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create('Magento\Tax\Test\TestStep\DeleteAllTaxRulesStep')->run();
    }
}
