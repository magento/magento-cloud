<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\TestCase;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\Mtf\ObjectManager;
use Magento\CustomerCustomAttributes\Test\TestStep\DeleteCustomerAddressAttributeStep;
use Magento\Customer\Test\Fixture\Address;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Customer\Test\Page\CustomerAddressEdit;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 *
 * 1. Created new custom address attribute.
 *
 * Steps:
 * 1. Go to Frontend.
 * 2. Fill required address fields and created customer custom address field
 * 3. Create a product
 * 4. Add a product to cart
 * 5. Navigate to checkout page
 * 6. Perform assertions
 *
 * @group Customer_Attributes
 * @ZephyrId MAGETWO-60974
 */
class CreateCustomAddressAttributeTest extends Injectable
{
    /**
     * CustomerAccountIndex page.
     *
     * @var CustomerAccountIndex
     */
    private $customerAccountIndex;

    /**
     * CustomerAddressEdit page.
     *
     * @var CustomerAddressEdit
     */
    private $customerAddressEdit;

    /**
     * @var Address
     */
    private $address;

    /**
     * Preparing data for test.
     *
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerAddressEdit $customerAddressEdit
     * @return void
     */
    public function __inject(
        CustomerAccountIndex $customerAccountIndex,
        CustomerAddressEdit $customerAddressEdit
    ) {
        $this->customerAccountIndex = $customerAccountIndex;
        $this->customerAddressEdit = $customerAddressEdit;
    }

    /**
     * Run Create Customer Custom Address Attribute.
     *
     * @param Customer $customer
     * @param Address $address
     * @return array
     */
    public function test(
        Customer $customer,
        Address $address
    ) {
        // Preconditions
        $customer->persist();

        // Steps
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $this->customerAccountIndex->getDashboardAddress()->editBillingAddress();
        $this->customerAddressEdit->getEditForm()->fill($address);
        $this->customerAddressEdit->getEditForm()->saveAddress();

        $this->address = $address;

        return ['customer' => $customer, 'address' => $address];
    }

    /**
     * Delete created customer address attribute.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(
            DeleteCustomerAddressAttributeStep::class,
            [
                'customAttribute' => $this->objectManager->create(
                    CustomerAddressAttribute::class,
                    [
                        'data' => [
                            'attribute_code' => $this->address->getCustomAttribute()['code']
                        ]
                    ]
                )
            ]
        )->run();
    }
}
