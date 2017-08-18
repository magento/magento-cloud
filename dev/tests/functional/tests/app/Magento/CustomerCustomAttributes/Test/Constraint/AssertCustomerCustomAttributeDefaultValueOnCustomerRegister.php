<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerCustomAttributeDefaultValueOnCustomerRegister
 * Assert that created customer attribute has default value during register customer on frontend
 */
class AssertCustomerCustomAttributeDefaultValueOnCustomerRegister extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that created customer attribute has default value during register customer on frontend
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountCreate $customerAccountCreate
     * @param CustomerCustomAttribute $customerAttribute
     * @param CustomerCustomAttribute $initialCustomerAttribute
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CustomerAccountCreate $customerAccountCreate,
        CustomerCustomAttribute $customerAttribute,
        CustomerCustomAttribute $initialCustomerAttribute = null
    ) {
        $customerAttribute = $initialCustomerAttribute === null ? $customerAttribute : $initialCustomerAttribute;
        $cmsIndex->open();
        $cmsIndex->getLinksBlock()->openLink('Create an Account');
        $key = current(preg_grep('/^default_value_/', array_keys($customerAttribute->getData())));
        \PHPUnit_Framework_Assert::assertEquals(
            $customerAccountCreate->getCustomerAttributesRegisterForm()->getCustomerAttributeValue($customerAttribute),
            $customerAttribute->getData()[$key],
            'Customer Custom Attribute with attribute code: \'' . $customerAttribute->getAttributeCode() . '\' '
            . 'has wrong default value on customer register form.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute default value is present during register customer on frontend.';
    }
}
