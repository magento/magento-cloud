<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Test\TestCase;

use Magento\Sales\Test\Fixture\OrderCheckout;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\TestCase\Functional;

/**
 * Abstract class for refund tests.
 */
abstract class AbstractRefundTest extends Functional
{
    /**
     * Sets up the preconditions for the refund tests.
     *
     * @param OrderCheckout $fixture
     * @return void
     */
    public function setupPreconditions(OrderCheckout $fixture)
    {
        // Enable returns
        $enableRma = Factory::getFixtureFactory()->getMagentoConfigConfig();
        $enableRma->switchData('enable_rma');
        $enableRma->persist();

        // Create an order.
        $fixture->persist();

        // Log into the backend.
        Factory::getApp()->magentoBackendLoginUser();

        // Close the order.
        Factory::getApp()->magentoSalesCloseOrder($fixture);
    }
}
