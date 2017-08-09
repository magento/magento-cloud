<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create Gift Wrapping according dataset.
 * 2. Create Product according dataset.
 *
 * Steps:
 * 1. Open created Product page.
 * 2. Add product to Cart.
 * 3. Perform all asserts.
 *
 * @group Gift_Wrapping
 * @ZephyrId MAGETWO-69276
 */
class CheckoutWithGiftWrappingTest extends Scenario
{
    /**
     * Checkout with Gift Wrapping test.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
