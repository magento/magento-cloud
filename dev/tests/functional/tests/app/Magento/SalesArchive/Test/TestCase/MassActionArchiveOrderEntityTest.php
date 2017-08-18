<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\SalesArchive\Test\Page\Adminhtml\ArchiveOrders;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Enable "Orders Archiving"
 * 2. Enable payment method "Check/Money Order"
 * 3. Enable shipping method Flat Rate
 * 4. Create a product
 * 5. Create a customer
 * 6. Place orders (and do actions according to dataset - invoice, shipment)
 * 7. Move orders to Archive
 *
 * Steps:
 * 1. Go to Admin > Sales > Archive >Orders
 * 2. Select orders and in the 'Actions' drop-down select action according to dataset
 * 3. Click 'Submit' button
 * 4. Perform all assertions
 *
 * @group Sales_Archive
 * @ZephyrId MAGETWO-28873
 */
class MassActionArchiveOrderEntityTest extends Injectable
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
     * Archive Orders Page.
     *
     * @var ArchiveOrders
     */
    protected $archiveOrders;

    /**
     * Factory for fixtures.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Enable "Check/Money Order", "Flat Rate", "Sales Archive" in configuration.
     *
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $this->fixtureFactory = $fixtureFactory;
        $processStep = $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'checkmo, flatrate, salesarchive_all_statuses']
        );
        $processStep->run();
    }

    /**
     * Injection data.
     *
     * @param OrderIndex $orderIndex
     * @param ArchiveOrders $archiveOrders
     * @return void
     */
    public function __inject(OrderIndex $orderIndex, ArchiveOrders $archiveOrders)
    {
        $this->orderIndex = $orderIndex;
        $this->archiveOrders = $archiveOrders;
    }

    /**
     * Move order to archive.
     *
     * @param string $steps
     * @param int $ordersQty
     * @param string $massAction
     * @return array
     */
    public function test($steps, $ordersQty, $massAction)
    {
        // Preconditions
        $orders = $this->prepareOrders($ordersQty);

        // Steps
        $steps = explode(';', $steps);
        $this->orderIndex->open();
        foreach ($orders['orders'] as $key => $order) {
            $this->processSteps($order, trim($steps[$key]));
        }
        $this->orderIndex->open();
        $this->orderIndex->getSalesOrderGrid()->massaction($orders['ordersIds'], 'Move to Archive');
        $this->archiveOrders->open();
        $this->archiveOrders->getSalesArchiveOrderGrid()->massaction($orders['ordersIds'], $massAction);

        return $orders;
    }

    /**
     * Process which step to take for order
     *
     * @param OrderInjectable $order
     * @param string $steps
     * @return array
     */
    protected function processSteps(OrderInjectable $order, $steps)
    {
        $products = $order->getEntityId()['products'];
        $cart['data']['items'] = ['products' => $products];
        $cart = $this->fixtureFactory->createByCode('cart', $cart);
        $steps = array_diff(explode(',', $steps), ['-']);
        foreach ($steps as $step) {
            $action = str_replace(' ', '', ucwords($step));
            $methodAction = $action === 'Hold' ? 'On' : 'Create';
            $methodAction .= $action . 'Step';
            $path = 'Magento\Sales\Test\TestStep';
            $processStep = $this->objectManager->create(
                $path . '\\' . $methodAction,
                ['order' => $order, 'cart' => $cart]
            );
            $processStep->run();
        }
    }

    /**
     * Prepare orders for test.
     *
     * @param int $ordersQty
     * @return array
     */
    protected function prepareOrders($ordersQty)
    {
        $result = [];

        for (; $ordersQty > 0; $ordersQty--) {
            /** @var OrderInjectable $order */
            $order = $this->fixtureFactory->createByCode('orderInjectable');
            $order->persist();
            $result['orders'][] = $order;
            $result['ordersIds'][] = ['id' => $order->getId()];
        }

        return $result;
    }
}
