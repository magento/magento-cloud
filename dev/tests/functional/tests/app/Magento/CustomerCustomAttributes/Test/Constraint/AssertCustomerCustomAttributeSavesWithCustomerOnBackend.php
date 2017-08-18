<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Assert that we can save the customer with attribute code with digit at the end.
 */
class AssertCustomerCustomAttributeSavesWithCustomerOnBackend extends AbstractConstraint
{
    /**
     * Assert that we can save the customer with attribute code with digit at the end.
     *
     * @param CustomerIndexEdit $customerIndexEdit
     * @param Customer $customer
     * @param TestStepFactory $testStepFactory
     * @param $customerAttribute
     */
    public function processAssert(
        CustomerIndexEdit $customerIndexEdit,
        Customer $customer,
        TestStepFactory $testStepFactory,
        CustomerCustomAttribute $customerAttribute
    ) {
        $testStepFactory->create(
            \Magento\Customer\Test\TestStep\OpenCustomerOnBackendStep::class,
            ['customer' => $customer]
        )->run();

        $customerForm = $customerIndexEdit->getCustomerForm();

        $customerForm->openTab('account_information');
        $customerForm->getTab('account_information')->setFieldsData(
            [
                $customerAttribute->getAttributeCode() => 'some value'
            ]
        );

        $customerIndexEdit->getPageActionsBlock()->save();
        $customerIndexEdit->getMessagesBlock()->assertSuccessMessage();
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer save was successful with new attribute.';
    }
}
