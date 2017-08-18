<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Sales\Test\Page\Adminhtml\SalesOrderView;
use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Shipping\Test\Page\Adminhtml\OrderShipmentNew;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Preconditions:
 * 1. Enable "Orders Archiving" for all statuses in configuration
 * 2. Enable payment method "Check/Money Order"
 * 3. Enable shipping method Flat Rate
 * 4. Place order with product qty = 2
 * 5. Invoice order with 2 products
 * 6. Move orders to Archive
 *
 * Steps:
 * 1. Go to Admin > Sales > Archive > Orders
 * 2. Select orders and do Shipment
 * 3. Fill data from dataset
 * 4. Click 'Submit' button
 * 5. Perform all assertions
 *
 * @group Sales_Archive
 * @ZephyrId MAGETWO-28781
 */
class ShipmentSalesArchiveEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Orders Page.
     *
     * @var OrderIndex
     */
    protected $orderIndex;

    /**
     * Archive orders page.
     *
     * @var ArchiveOrders
     */
    protected $archiveOrders;

    /**
     * Order View Page.
     *
     * @var SalesOrderView
     */
    protected $salesOrderView;

    /**
     * New Order Shipment Page.
     *
     * @var OrderShipmentNew
     */
    protected $orderShipmentNew;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Enable "Check/Money Order", "Flat Rate" and archiving for all statuses in configuration.
     *
     * @return void
     */
    public function __prepare()
    {
        $setupConfig = $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'salesarchive_all_statuses, checkmo, flatrate']
        );
        $setupConfig->run();
    }

    /**
     * Injection data.
     *
     * @param FixtureFactory $fixtureFactory
     * @param OrderIndex $orderIndex
     * @param ArchiveOrders $archiveOrders
     * @param SalesOrderView $salesOrderView
     * @param OrderShipmentNew $orderShipmentNew
     * @return void
     */
    public function __inject(
        FixtureFactory $fixtureFactory,
        OrderIndex $orderIndex,
        ArchiveOrders $archiveOrders,
        SalesOrderView $salesOrderView,
        OrderShipmentNew $orderShipmentNew
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $this->orderIndex = $orderIndex;
        $this->archiveOrders = $archiveOrders;
        $this->salesOrderView = $salesOrderView;
        $this->orderShipmentNew = $orderShipmentNew;
    }

    /**
     * Create Shipment SalesArchive Entity.
     *
     * @param OrderInjectable $order
     * @param string $invoice
     * @param array $data
     * @return array
     */
    public function test(OrderInjectable $order, $invoice, array $data)
    {
        // Preconditions
        $order->persist();
        if ($invoice) {
            $products = $order->getEntityId()['products'];
            $cart['data']['items'] = ['products' => $products];
            $cart = $this->fixtureFactory->createByCode('cart', $cart);
            $invoice = $this->objectManager->create(
                \Magento\Sales\Test\TestStep\CreateInvoiceStep::class,
                ['order' => $order, 'cart' => $cart]
            );
            $invoice->run();
        }
        $this->orderIndex->open();
        $this->orderIndex->getSalesOrderGrid()->massaction([['id' => $order->getId()]], 'Move to Archive');

        // Steps
        $this->archiveOrders->open();
        $this->archiveOrders->getSalesArchiveOrderGrid()->searchAndOpen(['id' => $order->getId()]);
        $this->salesOrderView->getPageActions()->ship();
        $this->orderShipmentNew->getFormBlock()->fillData($data, $order->getEntityId()['products']);
        $this->orderShipmentNew->getFormBlock()->submit();

        $this->salesOrderView->getOrderForm()->openTab('shipments');
        $shipmentIds = $this->salesOrderView->getOrderForm()->getTab('shipments')->getGridBlock()->getIds();

        return [
            'ids' => ['shipmentIds' => $shipmentIds],
            'order' => $order
        ];
    }
}
