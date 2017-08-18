<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create Tax Rate
 * 2. Create Tax Rule with the Tax Rate from step 1
 * 3. Create a simple product
 * 4. Create a Customer
 * 5. Create new Gift Wrapping
 *
 * Steps:
 * 1. Open Backend.
 * 2. Open Sales Order Grid Sales > Orders
 * 3. Create button "Create New Order"
 * 4. Select a customer
 * 5. Add product to cart
 * 6. Fill Account information
 * 7. Fill Billing information
 * 6. Select gift wrapping
 * 7. Select shipping method
 * 8. Remove gift wrapping of cart item
 * 9. Perform all assertions.
 *
 * @group Gift_Wrapping
 * @ZephyrId MAGETWO-63184
 */
class CheckoutWithGiftWrappingAdminTest extends Scenario
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Create Order with Gift Wrapping Incl Tax test.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
