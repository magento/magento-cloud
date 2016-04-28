<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Sales\Test\Page\Adminhtml\OrderInvoiceNew;
use Magento\Sales\Test\Page\Adminhtml\SalesOrderView;
use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Enable "Orders Archiving" for all statuses in configuration
 * 2. Enable payment method "Check/Money Order"
 * 3. Enable shipping method Flat Rate
 * 4. Place order with product qty = 2
 * 5. Move order to Archive
 *
 * Steps:
 * 1. Go to Admin > Sales > Archive > Orders
 * 2. Select orders and do Invoice
 * 3. Fill data from dataset
 * 4. Click 'Submit' button
 * 5. Perform all assertions
 *
 * @group Sales_Archive_(CS)
 * @ZephyrId MAGETWO-28947
 */
class InvoiceSalesArchiveEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS';
    /* end tags */

    /**
     * Orders page.
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
     * Order view page.
     *
     * @var SalesOrderView
     */
    protected $salesOrderView;

    /**
     * Order new invoice page.
     *
     * @var OrderInvoiceNew
     */
    protected $orderInvoiceNew;

    /**
     * Enable "Check/Money Order", "Flat Rate" and archiving for all statuses in configuration.
     *
     * @return void
     */
    public function __prepare()
    {
        $setupConfig = $this->objectManager->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'salesarchive_all_statuses, checkmo, flatrate']
        );
        $setupConfig->run();
    }

    /**
     * Injection data.
     *
     * @param OrderIndex $orderIndex
     * @param ArchiveOrders $archiveOrders
     * @param SalesOrderView $salesOrderView
     * @param OrderInvoiceNew $orderInvoiceNew
     * @return void
     */
    public function __inject(
        OrderIndex $orderIndex,
        ArchiveOrders $archiveOrders,
        SalesOrderView $salesOrderView,
        OrderInvoiceNew $orderInvoiceNew
    ) {
        $this->orderIndex = $orderIndex;
        $this->archiveOrders = $archiveOrders;
        $this->salesOrderView = $salesOrderView;
        $this->orderInvoiceNew = $orderInvoiceNew;
    }

    /**
     * Create Invoice SalesArchive Entity.
     *
     * @param OrderInjectable $order
     * @param array $data
     * @return array
     */
    public function test(OrderInjectable $order, array $data)
    {
        // Preconditions
        $order->persist();
        $this->orderIndex->open();
        $this->orderIndex->getSalesOrderGrid()->massaction([['id' => $order->getId()]], 'Move to Archive');

        // Steps
        $this->archiveOrders->open();
        $this->archiveOrders->getSalesArchiveOrderGrid()->searchAndOpen(['id' => $order->getId()]);
        $this->salesOrderView->getPageActions()->invoice();
        $this->orderInvoiceNew->getFormBlock()->fillProductData($data, $order->getEntityId()['products']);
        $this->orderInvoiceNew->getFormBlock()->updateQty();
        $this->orderInvoiceNew->getFormBlock()->fillFormData($this->data);
        $this->orderInvoiceNew->getFormBlock()->submit();

        $this->salesOrderView->getOrderForm()->openTab('invoices');
        $invoiceIds = $this->salesOrderView->getOrderForm()->getTab('invoices')->getGridBlock()->getIds();

        return [
            'ids' => ['invoiceIds' => $invoiceIds],
            'orderId' => $order->getId()
        ];
    }
}
