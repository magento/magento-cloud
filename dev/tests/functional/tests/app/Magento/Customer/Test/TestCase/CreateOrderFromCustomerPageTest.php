<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Customer\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create customer.
 *
 * Steps:
 * 1. Open Backend.
 * 2. Go to Customers -> All Customers.
 * 3. Select and open customer from the grid.
 * 4. Click Create Order button.
 * 5. Click Add Products.
 * 6. Fill data according dataset.
 * 7. Click Update Product qty.
 * 8. Fill data according dataset.
 * 9. Click Get Shipping Method and rates.
 * 10. Fill data according dataset.
 * 11. Click Submit Order.
 * 12. Perform all assertions.
 *
 * @group Order_Management
 * @ZephyrId MAGETWO-28960
 */
class CreateOrderFromCustomerPageTest extends Scenario
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Runs sales order on backend.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
