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
 * Class AssertCustomerBalanceHistory
 * Assert that customer balance history is changed
 */
class AssertCustomerBalanceHistory extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that customer balance history is changed
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
            $customerIndexEdit->getBalanceHistoryGrid()->verifyCustomerBalanceGrid($customerBalance),
            '"Balance History" grid not contains correct information.'
        );
    }

    /**
     * Assert that customer balance history is changed succeed
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer balance history is changed.';
    }
}
