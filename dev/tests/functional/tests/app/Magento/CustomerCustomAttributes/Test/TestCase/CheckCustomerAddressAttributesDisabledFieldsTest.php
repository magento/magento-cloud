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
 * 2. Go to Stores > Customer Address.
 * 3. Open each existing system attributes by its code.
 * 4. Check fields statuses for system customer address attributes.
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-61062
 */
class CheckCustomerAddressAttributesDisabledFieldsTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Check disabled fields for system customer address attributes.
     *
     * @return void
     */
    public function test()
    {
        // Test only requires an assertion
    }
}
