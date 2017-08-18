<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;

/**
 * Steps:
 * 1. Open Backend.
 * 2. Go to Stores > Customer.
 * 3. Open each existing system attribute by its code.
 * 4. Check fields statuses for system customer attributes.
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-61063
 */
class CheckCustomerAttributesDisabledFieldsTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Check disabled fields for system customer attributes.
     *
     * @return void
     */
    public function test()
    {
        // Test only requires an assertion
    }
}
