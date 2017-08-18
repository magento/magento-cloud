<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerBalanceAmount
 * Assert that customer balance amount is changed
 */
class AssertCustomerBalanceAmount extends AbstractConstraint
{
    /**
     * Assert that customer balance amount is changed
     *
     * @param CustomerIndex $customerIndex
     * @param Customer $customer
     * @param CustomerBalance $customerBalance
     * @param CustomerIndexEdit $customerIndexEdit
     * @return void
     */
    public function processAssert(
        CustomerIndex $customerIndex,
        Customer $customer,
        CustomerBalance $customerBalance,
        CustomerIndexEdit $customerIndexEdit
    ) {
        $customerIndex->open();
        $filter = ['email' => $customer->getEmail()];
        $customerIndex->getCustomerGridBlock()->searchAndOpen($filter);
        $customerForm = $customerIndexEdit->getCustomerBalanceForm();
        $customerForm->openTab('store_credit');

        \PHPUnit_Framework_Assert::assertTrue(
            $customerForm->getStoreCreditTab()->isStoreCreditBalance($customerBalance->getBalanceDelta()),
            '"Store Credit Balance" grid not displays total amount of store credit balance.'
        );
    }

    /**
     * Customer balance amount is changed
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer balance amount has been updated.';
    }
}
