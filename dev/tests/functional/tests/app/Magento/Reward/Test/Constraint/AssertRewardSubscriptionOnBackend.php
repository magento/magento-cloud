<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\Reward\Test\Fixture\Reward;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Class AssertRewardSubscriptionOnBackend
 * Assert that customer reward subscriptions checkboxes are empty
 */
class AssertRewardSubscriptionOnBackend extends AbstractAssertForm
{
    /**
     * Assert that customer reward subscriptions checkboxes are empty
     * On Customers->All Customers->%Customer%->Reward Points tab
     *
     * @param Customer $customer
     * @param CustomerIndex $customerIndex
     * @param CustomerIndexEdit $customerIndexEdit
     * @param Reward $reward
     * @return void
     */
    public function processAssert(
        Customer $customer,
        CustomerIndex $customerIndex,
        CustomerIndexEdit $customerIndexEdit,
        Reward $reward
    ) {
        $filter = ['email' => $customer->getEmail()];
        $customerIndex->open();
        $customerIndex->getCustomerGridBlock()->searchAndOpen($filter);
        $formData = $customerIndexEdit->getCustomerForm()->getData();
        $fixtureData = $reward->getData();
        $error = $this->verifyData($fixtureData, $formData);
        \PHPUnit_Framework_Assert::assertEmpty(
            $error,
            "Reward Points Subscription form was filled incorrectly.\nError:\n" . $error
        );
    }

    /**
     * Returns string representation of successful assertion
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer reward subscriptions checkboxes are unchecked.';
    }
}
