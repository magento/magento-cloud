<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 *
 * 1. Create Customer with one address in address book.
 * 2. Create Product.
 * 3. Create Customer Custom Address Attribute.
 *
 * Steps:
 * 1. Go to Frontend.
 * 2. Add product in cart and proceed to checkout.
 * 3. Click *New Address* button on 1st checkout step.
 * 4. Do not fill in required fields and click *Save address* button.
 * 5. Perform all assertions.
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-59593
 */
class ValidateRequireAddressAttributeEntityTest extends Scenario
{
    /**
     * Validation Require CustomerCustomAttributesEntity.
     *
     * @return void
     */
    public function test()
    {
        $this->executeScenario();
    }
}
