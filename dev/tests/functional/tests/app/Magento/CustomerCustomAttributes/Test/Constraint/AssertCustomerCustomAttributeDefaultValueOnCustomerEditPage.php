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
 * Class AssertCustomerCustomAttributeDefaultValueOnCustomerEditPage
 * Assert that created customer attribute is available during edit customer account on frontend
 */
class AssertCustomerCustomAttributeDefaultValueOnCustomerEditPage extends AbstractConstraint
{
    /**
     * Assert that created customer attribute has default value during edit customer account on frontend
     *
     * @param CustomerAccountLogin $customerAccountLogin
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerCustomAttribute $customerAttribute
     * @param CustomerAccountEdit $customerAccountEdit
     * @param Customer $customer
     * @param CustomerCustomAttribute $initialCustomerAttribute
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
        $customerAccountIndex->open();
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Account Information');
        $key = current(preg_grep('/^default_value_/', array_keys($customerAttribute->getData())));
        \PHPUnit_Framework_Assert::assertEquals(
            $customerAccountEdit->getAccountInfoForm()->getCustomerAttributeValue($customerAttribute),
            $customerAttribute->getData()[$key],
            'Customer Custom Attribute with attribute code: \'' . $customerAttribute->getAttributeCode() . '\' '
            . 'has wrong default value on customer edit page.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute has default value during editing customer account on frontend.';
    }
}
