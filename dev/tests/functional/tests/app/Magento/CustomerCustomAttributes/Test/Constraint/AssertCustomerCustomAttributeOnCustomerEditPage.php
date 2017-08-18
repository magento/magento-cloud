<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountEdit;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Customer\Test\Page\CustomerAccountLogin;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerCustomAttributeOnCustomerEditPage
 * Assert that created customer attribute is available during edit customer account on frontend
 */
class AssertCustomerCustomAttributeOnCustomerEditPage extends AbstractConstraint
{
    /**
     * Assert that created customer attribute is available during edit customer account on frontend
     *
     * @param CustomerAccountLogin $customerAccountLogin
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerCustomAttribute $customerAttribute
     * @param CustomerAccountEdit $customerAccountEdit
     * @param Customer $customer
     * @param CustomerCustomAttribute $initialCustomerAttribute
     * @return void
     */
    public function processAssert(
        CustomerAccountLogin $customerAccountLogin,
        CustomerAccountIndex $customerAccountIndex,
        CustomerCustomAttribute $customerAttribute,
        CustomerAccountEdit $customerAccountEdit,
        Customer $customer,
        CustomerCustomAttribute $initialCustomerAttribute = null
    ) {
        $customerAttribute = $initialCustomerAttribute === null ? $customerAttribute : $initialCustomerAttribute;
        $customerAccountLogin->open();
        $customerAccountLogin->getLoginBlock()->fill($customer);
        $customerAccountLogin->getLoginBlock()->submit();
        $customerAccountIndex->open();
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Account Information');
        \PHPUnit_Framework_Assert::assertTrue(
            $customerAccountEdit->getAccountInfoForm()->isCustomerAttributeVisible($customerAttribute),
            'Customer Custom Attribute with attribute code: \'' . $customerAttribute->getAttributeCode() . '\' '
            . 'is absent during register customer on frontend.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute is present during editing customer account on frontend.';
    }
}
