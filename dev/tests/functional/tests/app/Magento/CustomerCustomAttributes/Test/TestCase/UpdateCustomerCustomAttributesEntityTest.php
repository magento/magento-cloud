<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;

/**
 * Preconditions:
 * 1. Create customer attribute
 *
 * Steps:
 * 1. Open Backend
 * 2. Go to Stores> Customer
 * 3. Open created customer attribute
 * 4. Fill data according to dataset
 * 5. Save attribute
 * 6. Perform all assertions
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-26366
 */
class UpdateCustomerCustomAttributesEntityTest extends AbstractCustomerCustomAttributesEntityTest
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Update custom Customer Attribute.
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @param CustomerCustomAttribute $initialCustomerAttribute
     * @return array
     */
    public function test(
        CustomerCustomAttribute $customerAttribute,
        CustomerCustomAttribute $initialCustomerAttribute
    ) {
        // Preconditions
        $initialCustomerAttribute->persist();
        $this->customerCustomAttribute = $initialCustomerAttribute;

        // Steps
        $filter = ['attribute_code' => $initialCustomerAttribute->getAttributeCode()];
        $this->customerAttributeIndex->open();
        $this->customerAttributeIndex->getCustomerCustomAttributesGrid()->searchAndOpen($filter);
        $this->customerAttributeNew->getCustomerCustomAttributesForm()->fill($customerAttribute);
        $this->customerAttributeNew->getFormPageActions()->save();

        // Prepare data for tear down
        $this->customerCustomAttribute = $customerAttribute;
    }
}
