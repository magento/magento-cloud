<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\User\Test\Fixture\User;
use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Sales\Test\Constraint\AssertRefundInRefundsGrid;
use Magento\Sales\Test\Constraint\AssertRefundNotInRefundsGrid;
use Magento\Sales\Test\Constraint\AssertInvoiceInInvoicesGrid;
use Magento\Sales\Test\Constraint\AssertInvoiceNotInInvoicesGrid;
use Magento\Shipping\Test\Constraint\AssertShipmentInShipmentsGrid;
use Magento\Shipping\Test\Constraint\AssertShipmentNotInShipmentsGrid;
use Magento\Sales\Test\Page\Adminhtml\CreditMemoIndex;
use Magento\Sales\Test\Page\Adminhtml\InvoiceIndex;
use Magento\Shipping\Test\Page\Adminhtml\ShipmentIndex;

/**
 * Create 2 orders in different websites, login with custom admin user
 * and verify that orders, invoices, shipments and credit memos are visible/not visible in grid
 * according to AdminGws role settings.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AssertAdminGwsSales extends AbstractConstraint
{
    /**
     * Factory for Test Steps.
     *
     * @var TestStepFactory
     */
    private $testStepFactory;

    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    private $fixtureFactory;

    /**
     * Orders Page.
     *
     * @var OrderIndex
     */
    private $orderIndex;

    /**
     * Credit Page.
     *
     * @var CreditMemoIndex
     */
    private $creditMemoIndex;

    /**
     * Invoice Index Page.
     *
     * @var InvoiceIndex
     */
    private $invoiceIndex;

    /**
     * Shipment Index Page.
     *
     * @var ShipmentIndex
     */
    private $shipmentIndex;

    /**
     * Shipment ids array.
     *
     * @var array
     */
    private $shipmentIds = [];

    /**
     * Invoice ids array.
     *
     * @var array
     */
    private $invoiceIds = [];

    /**
     * Credit memo ids array.
     *
     * @var array
     */
    private $creditMemoIds = [];

    /**
     * Injection data.
     *
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param OrderIndex $orderIndex
     * @param CreditMemoIndex $creditMemoIndex
     * @param InvoiceIndex $invoiceIndex
     * @param ShipmentIndex $shipmentIndex
     * @return void
     */
    public function __inject(
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        OrderIndex $orderIndex,
        CreditMemoIndex $creditMemoIndex,
        InvoiceIndex $invoiceIndex,
        ShipmentIndex $shipmentIndex
    ) {
        $this->testStepFactory = $testStepFactory;
        $this->fixtureFactory = $fixtureFactory;
        $this->orderIndex = $orderIndex;
        $this->creditMemoIndex = $creditMemoIndex;
        $this->invoiceIndex = $invoiceIndex;
        $this->shipmentIndex = $shipmentIndex;
    }

    /**
     * Asserts orders, invoices, shipments and credit memos visibility in grid.
     *
     * @param User $customAdmin
     * @param OrderInjectable $invisibleOrder
     * @param AssertRefundInRefundsGrid $assertRefundInRefundsGrid
     * @param AssertRefundNotInRefundsGrid $assertRefundNotInRefundsGrid
     * @param AssertInvoiceInInvoicesGrid $assertInvoiceInInvoicesGrid
     * @param AssertInvoiceNotInInvoicesGrid $assertInvoiceNotInInvoicesGrid
     * @param AssertShipmentInShipmentsGrid $assertShipmentInShipmentsGrid
     * @param AssertShipmentNotInShipmentsGrid $assertShipmentNotInShipmentsGrid
     * @param string $visibleOrder
     * @return void
     */
    public function processAssert(
        User $customAdmin,
        OrderInjectable $invisibleOrder,
        AssertRefundInRefundsGrid $assertRefundInRefundsGrid,
        AssertRefundNotInRefundsGrid $assertRefundNotInRefundsGrid,
        AssertInvoiceInInvoicesGrid $assertInvoiceInInvoicesGrid,
        AssertInvoiceNotInInvoicesGrid $assertInvoiceNotInInvoicesGrid,
        AssertShipmentInShipmentsGrid $assertShipmentInShipmentsGrid,
        AssertShipmentNotInShipmentsGrid $assertShipmentNotInShipmentsGrid,
        $visibleOrder
    ) {
        $orders = $this->prepareDataForConstraint(
            $invisibleOrder,
            $customAdmin,
            $visibleOrder
        );
        $this->testStepFactory->create(
            \Magento\User\Test\TestStep\LoginUserOnBackendStep::class,
            ['user' => $customAdmin]
        )->run();
        foreach ($orders as $order) {
            $this->orderIndex->open();
            if ($order['visible']) {
                \PHPUnit_Framework_Assert::assertTrue(
                    $this->orderIndex->getSalesOrderGrid()->isRowVisible(['id' => $order['order']->getId()]),
                    'Order with following id ' . $order['order']->getId() . ' is absent in orders grid.'
                );
                $assertRefundInRefundsGrid->processAssert(
                    $this->creditMemoIndex,
                    $order['order'],
                    $this->creditMemoIds[$order['order']->getId()]
                );
                $assertInvoiceInInvoicesGrid->processAssert(
                    $this->invoiceIndex,
                    $order['order'],
                    $this->invoiceIds[$order['order']->getId()]
                );
                $assertShipmentInShipmentsGrid->processAssert(
                    $this->shipmentIndex,
                    $order['order'],
                    $this->shipmentIds[$order['order']->getId()]
                );
            } else {
                \PHPUnit_Framework_Assert::assertFalse(
                    $this->orderIndex->getSalesOrderGrid()->isRowVisible(['id' => $order['order']->getId()]),
                    'Order with following id ' . $order['order']->getId() . ' is present in orders grid.'
                );
                $assertRefundNotInRefundsGrid->processAssert(
                    $this->creditMemoIndex,
                    $order['order'],
                    $this->creditMemoIds[$order['order']->getId()]
                );
                $assertInvoiceNotInInvoicesGrid->processAssert(
                    $this->invoiceIndex,
                    $order['order'],
                    $this->invoiceIds[$order['order']->getId()]
                );
                $assertShipmentNotInShipmentsGrid->processAssert(
                    $this->shipmentIndex,
                    $order['order'],
                    $this->shipmentIds[$order['order']->getId()]
                );
            }
        }
    }

    /**
     * Prepare data for constraint.
     *
     * @param OrderInjectable $invisibleOrder
     * @param User $customAdmin
     * @param string $visibleOrder
     * @return array
     */
    private function prepareDataForConstraint(
        OrderInjectable $invisibleOrder,
        User $customAdmin,
        $visibleOrder
    ) {
        $invisibleOrder->persist();
        $adminGwsRole = $customAdmin->getDataFieldConfig('role_id')['source']->getRole();
        $store = $adminGwsRole->getDataFieldConfig('gws_stores')['source']->getStores()[0];
        $visibleOrder = $this->fixtureFactory->createByCode(
            'orderInjectable',
            [
                'dataset' => $visibleOrder,
                'data' => [
                    'store_id' => ['store' => $store],
                ]
            ]
        );
        $visibleOrder->persist();
        $orders = [$invisibleOrder, $visibleOrder];
        foreach ($orders as $order) {
            $products = $order->getEntityId()['products'];
            $cart['data']['items'] = ['products' => $products];
            $cart = $this->fixtureFactory->createByCode('cart', $cart);
            $this->shipmentIds[$order->getId()] = $this->testStepFactory->create(
                \Magento\Sales\Test\TestStep\CreateShipmentStep::class,
                ['order' => $order]
            )->run();
            $this->invoiceIds[$order->getId()] = $this->testStepFactory->create(
                \Magento\Sales\Test\TestStep\CreateInvoiceStep::class,
                ['order' => $order, 'cart' => $cart]
            )->run()['ids'];
            $this->creditMemoIds[$order->getId()] = $this->testStepFactory->create(
                \Magento\Sales\Test\TestStep\CreateCreditMemoStep::class,
                ['order' => $order, 'cart' => $cart]
            )->run();
        }

        return [
            [
                'order' => $invisibleOrder,
                'visible' => false
            ],
            [
                'order' => $visibleOrder,
                'visible' => true
            ]
        ];
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Orders, invoices, shipments and credit memos are visible in grid according to AdminGws role settings.';
    }
}
