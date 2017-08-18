<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Worldpay\Test\TestCase;

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
 * 7. Click 'Continue to Worldpay' button.
 * 8. Select credit card type.
 * 9. Specify credit card data.
 * 10. Click 'Make Payment' button.
 * 11. Perform assertions.
 *
 * @group Worldpay
 * @ZephyrId MAGETWO-49320
 */
class OnePageCheckoutWithWorldpayTest extends Scenario
{
    /* tags */
    const TEST_TYPE = '3rd_party_test';
    const SEVERITY = 'S0';
    /* end tags */

    /**
     * One page checkout using Worldpay.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
