<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeIndex;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create customer attribute
 *
 * Steps:
 * 1. Open Backend
 * 2. Go to Stores > Customers
 * 3. Open created customer attribute
 * 4. Click "Delete Attribute"
 * 5. Perform all assertions
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-26619
 */
class DeleteCustomerCustomAttributesEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * Backend page with the list of customer attributes.
     *
     * @var CustomerAttributeIndex
     */
    protected $customerAttributeIndex;

    /**
     * Backend page with new customer attribute form.
     *
     * @var CustomerAttributeNew
     */
    protected $customerAttributeNew;

    /**
     * Fixture CustomerCustomAttribute.
     *
     * @var CustomerCustomAttribute
     */
    protected $customerCustomAttribute;

    /**
     * Preparing customer
     *
     * @param Customer $customer
     * @return array
     */
    public function __prepare(Customer $customer)
    {
        $customer->persist();
        return ['customer' => $customer];
    }

    /**
     * Injection data.
     *
     * @param CustomerAttributeIndex $customerAttributeIndex
     * @param CustomerAttributeNew $customerAttributeNew
     * @return void
     */
    public function __inject(
        CustomerAttributeIndex $customerAttributeIndex,
        CustomerAttributeNew $customerAttributeNew
    ) {
        $this->customerAttributeIndex = $customerAttributeIndex;
        $this->customerAttributeNew = $customerAttributeNew;
    }

    /**
     * Delete custom Customer Attribute.
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @return void
     */
    public function test(CustomerCustomAttribute $customerAttribute)
    {
        // Precondition
        $customerAttribute->persist();

        // Steps
        $filter = ['attribute_code' => $customerAttribute->getAttributeCode()];
        $this->customerAttributeIndex->open();
        $this->customerAttributeIndex->getCustomerCustomAttributesGrid()->searchAndOpen($filter);
        $this->customerAttributeNew->getFormPageActions()->delete();
        $this->customerAttributeNew->getModalBlock()->acceptAlert();
    }
}
