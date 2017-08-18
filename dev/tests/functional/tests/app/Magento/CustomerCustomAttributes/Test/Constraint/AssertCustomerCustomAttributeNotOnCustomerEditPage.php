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
use Magento\Mtf\ObjectManager;

/**
 * Class AssertCustomerCustomAttributeNotOnCustomerEditPage
 * Assert that created customer attribute is absent during edit customer account on frontend
 */
class AssertCustomerCustomAttributeNotOnCustomerEditPage extends AbstractConstraint
{
    /**
     * Assert that created customer attribute is absent during edit customer account on frontend
     *
     * @param CustomerAccountLogin $customerAccountLogin
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerAccountEdit $customerAccountEdit
     * @param Customer $customer
     * @param CustomerCustomAttribute $customerAttribute
     * @param ObjectManager $objectManager
     * @return void
     */
    public function processAssert(
        CustomerAccountLogin $customerAccountLogin,
        CustomerAccountIndex $customerAccountIndex,
        CustomerAccountEdit $customerAccountEdit,
        Customer $customer,
        CustomerCustomAttribute $customerAttribute,
        ObjectManager $objectManager
    ) {
        $customerAccountLogin->open();
        $customerAccountLogin->getLoginBlock()->fill($customer);
        $customerAccountLogin->getLoginBlock()->submit();
        $customerAccountIndex->open();
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Account Information');
        $isCustomerAttributeVisible = $customerAccountEdit->getAccountInfoForm()
            ->isCustomerAttributeVisible($customerAttribute);
        $objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
        \PHPUnit_Framework_Assert::assertFalse(
            $isCustomerAttributeVisible,
            'Customer Custom Attribute with attribute code: \'' . $customerAttribute->getAttributeCode() . '\' '
            . 'is present during register customer on frontend.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute is absent during editing customer account on frontend.';
    }
}
