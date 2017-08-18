<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Eway\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Configure shipping method.
 * 2. Configure payment method.
 * 3. Create products.
 * 4. Create and setup customer.
 * 5. Create tax rule according to dataset.
 *
 * Steps:
 * 1. Log in Storefront.
 * 2. Add products to the Shopping Cart.
 * 3. Click the 'Proceed to Checkout' button.
 * 4. Fill shipping information.
 * 5. Select shipping method.
 * 6. Select payment method.
 * 7. Click 'Continue' button.
 * 9. Specify credit card data.
 * 10. Click 'Pay Now' button.
 * 11. Perform assertions.
 *
 * @group Eway
 * @ZephyrId MAGETWO-41667
 */
class OnePageCheckoutSharedPagesTest extends Scenario
{
    /* tags */
    const MVP = 'yes';
    const TEST_TYPE = '3rd_party_test';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Runs test for one page checkout using eWay Shared Pages payment method.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
