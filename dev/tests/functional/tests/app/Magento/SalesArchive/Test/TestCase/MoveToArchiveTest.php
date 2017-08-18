<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\ObjectManager;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Enable "Orders Archiving" in configuration
 * 2. Enable payment method "Check/Money Order"
 * 3. Enable shipping method Flat Rate
 * 4. Create a product
 * 5. Create a customer
 * 6. Place order (and do actions according to dataset - invoice, shipment, credit memo)
 *
 * Steps:
 * 1. Go to Admin > Sales > Orders
 * 2. Select placed orders and in the 'Actions' drop-down select 'Move to Archive' option
 * 3. Click 'Submit' button
 * 4. Perform all assertions
 *
 * @group Sales_Archive
 * @ZephyrId MAGETWO-28235
 */
class MoveToArchiveTest extends Injectable
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
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * @var ObjectManager.
     */
    protected $objectManager;

    /**
     * Enable Check/Money Order", "Flat Rate" in configuration.
     *
     * @param ObjectManager $objectManager
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __prepare(ObjectManager $objectManager, FixtureFactory $fixtureFactory)
    {
        $this->objectManager = $objectManager;
        $this->fixtureFactory = $fixtureFactory;

        $setupConfigurationStep = $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'checkmo, flatrate']
        );
        $setupConfigurationStep->run();
    }

    /**
     * Injection data.
     *
     * @param OrderIndex $orderIndex
     * @return void
     */
    public function __inject(OrderIndex $orderIndex)
    {
        $this->orderIndex = $orderIndex;
    }

    /**
     * Move order to archive.
     *
     * @param OrderInjectable $order
     * @param string $steps
     * @param string $configArchive
     * @return array
     */
    public function test(OrderInjectable $order, $steps, $configArchive)
    {
        // Preconditions
        $configPayment = $this->fixtureFactory->createByCode('configData', ['dataset' => $configArchive]);
        $configPayment->persist();

        $order->persist();

        // Steps
        $this->orderIndex->open();
        $ids = $this->processSteps($order, $steps);
        $this->orderIndex->open();
        $this->orderIndex->getSalesOrderGrid()->massaction([['id' => $order->getId()]], 'Move to Archive');

        return ['ids' => $ids];
    }

    /**
     * Process which step to take for order.
     *
     * @param OrderInjectable $order
     * @param string $steps
     * @param string $module
     * @return array
     */
    protected function processSteps(OrderInjectable $order, $steps, $module = 'Sales')
    {
        $products = $order->getEntityId()['products'];
        $cart['data']['items'] = ['products' => $products];
        $cart = $this->fixtureFactory->createByCode('cart', $cart);
        $steps = array_diff(explode(',', $steps), ['-']);
        $ids = [];
        foreach ($steps as $step) {
            $action = str_replace(' ', '', ucwords($step));
            $methodAction = 'Create' . $action . 'Step';
            $path = 'Magento\\' . $module . '\Test\TestStep';
            $processStep = $this->objectManager->create(
                $path . '\\' . $methodAction,
                ['order' => $order, 'cart' => $cart]
            )->run();
            if (isset($processStep['ids'])) {
                $ids = array_replace($ids, $processStep['ids']);
            }
        }

        return $ids;
    }
}
