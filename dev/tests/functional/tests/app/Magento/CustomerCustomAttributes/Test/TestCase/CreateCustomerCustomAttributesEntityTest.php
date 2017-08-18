<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;

/**
 * Test Flow:
 * 1. Log in to Backend
 * 2. Navigate to Stores > Customer
 * 3. Click "Add New Attribute"
 * 4. Fill data according to dataset
 * 5. Save attribute
 * 6. Perform all assertions
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-25963, MAGETWO-47711
 */
class CreateCustomerCustomAttributesEntityTest extends AbstractCustomerCustomAttributesEntityTest
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Create custom Customer Attribute.
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @return array
     */
    public function test(
        CustomerCustomAttribute $customerAttribute
    ) {
        // Steps
        $this->customerAttributeIndex->open();
        $this->customerAttributeIndex->getGridPageActions()->addNew();
        $this->customerAttributeNew->getCustomerCustomAttributesForm()->fill($customerAttribute);
        $this->customerAttributeNew->getFormPageActions()->save();

        // Prepare data for tear down
        $this->customerCustomAttribute = $customerAttribute;

        return [
            'customerAttribute' => $customerAttribute
        ];
    }
}
