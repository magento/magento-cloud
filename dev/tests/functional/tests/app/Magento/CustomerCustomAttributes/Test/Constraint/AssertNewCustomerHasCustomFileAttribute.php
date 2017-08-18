<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Assert that created customer has custom file attribute.
 */
class AssertNewCustomerHasCustomFileAttribute extends AbstractConstraint
{
    /**
     * Factory for fixtures.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Assert that created customer has custom file attribute.
     *
     * @param FixtureFactory $fixtureFactory
     * @param Customer $customer
     * @param CustomerIndexEdit $customerIndexEdit
     * @param CustomerCustomAttribute $customerAttribute
     * @param string $filePathToUpload
     * @return void
     */
    public function processAssert(
        FixtureFactory $fixtureFactory,
        Customer $customer,
        CustomerIndexEdit $customerIndexEdit,
        CustomerCustomAttribute $customerAttribute,
        $filePathToUpload
    ) {
        $this->fixtureFactory = $fixtureFactory;
        $customerIndexEdit->open(['id' => $customer->getId()]);
        $customer = $this->updateCustomer($customer, $filePathToUpload, $customerAttribute);
        $customerIndexEdit->getCustomerForm()->fillCustomer($customer);
        $customerIndexEdit->getPageActionsBlock()->saveAndContinue();

        \PHPUnit_Framework_Assert::assertEquals(
            basename($customer->getCustomAttribute()['value']),
            $customerIndexEdit->getCustomerForm()->getDataCustomer($customer)['customer']['custom_attribute'],
            'Uploaded file name wasn\'t saved.'
        );
    }

    /**
     * Merge Customer fixture with Custom Customer Attribute data.
     *
     * @param Customer $customer
     * @param string $value
     * @param CustomerCustomAttribute $customerAttribute
     * @return Customer
     */
    private function updateCustomer(Customer $customer, $value, CustomerCustomAttribute $customerAttribute)
    {
        $customAttribute = ['value' => $value, 'attribute' => $customerAttribute];
        $data = [
            'attribute' => $customerAttribute,
            'custom_attribute' => $customAttribute,
            'group_id' => [
                'customerGroup' => $customer->getDataFieldConfig('group_id')['source']->getCustomerGroup()
            ]
        ];
        $data = array_merge($customer->getData(), $data);

        return $this->fixtureFactory->createByCode('customer', ['data' => $data]);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer File Attribute is available in created customer.';
    }
}
