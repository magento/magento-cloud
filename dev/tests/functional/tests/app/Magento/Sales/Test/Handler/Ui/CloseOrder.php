<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\Handler\Ui;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Ui;

/**
 * Class CloseOrder
 * Closes a sales order
 *
 */
class CloseOrder extends Ui
{
    /**
     * Close sales order
     *
     * @param FixtureInterface $fixture [optional]
     * @return void
     */
    public function persist(FixtureInterface $fixture = null)
    {
        $orderId = $fixture->getOrderId();
        //Pages
        $orderPage = Factory::getPageFactory()->getSalesOrder();
        $newInvoicePage = Factory::getPageFactory()->getSalesOrderInvoiceNew();
        $newShipmentPage = Factory::getPageFactory()->getSalesOrderShipmentNew();

        Factory::getApp()->magentoBackendLoginUser();

        $orderPage->open();
        $orderPage->getOrderGridBlock()->searchAndOpen(['id' => $orderId]);

        //Create the Shipment
        $orderPage->getOrderActionsBlock()->ship();
        $newShipmentPage->getTotalsBlock()->submit();

        //Create the Invoice
        $orderPage->open();
        $orderPage->getOrderGridBlock()->searchAndOpen(['id' => $orderId]);
        $orderPage->getOrderActionsBlock()->invoice();
        $newInvoicePage->getTotalsBlock()->submit();
    }
}
