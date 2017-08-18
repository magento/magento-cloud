<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\CustomerBalance\Test\Fixture\CustomerBalance;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Cover creating CustomerBalanceEntity with fucntional tests designed for automation
 *
 * *Precondition:*
 * 1. Default customer is created
 *
 * Test Flow:
 * 1. Login to backend as admin
 * 2. Navigate to CUSTOMERS->All Customers
 * 3. Open customer from preconditions
 * 4. Open "Store Credit" tab
 * 5. Fill form with test data
 * 6. Click "Save Customer" button
 * 7. Preform asserts
 *
 * @group Customers
 * @ZephyrId MAGETWO-24387
 */
class CreateCustomerBalanceEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const STABLE = 'no';
    /* end tags */

    /**
     * Customer fixture
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Page of all customer grid
     *
     * @var CustomerIndex
     */
    protected $customerIndex;

    /**
     * Page of edit customer
     *
     * @var CustomerIndexEdit
     */
    protected $customerIndexEdit;

    /**
     * Prepare customer from preconditions
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $this->customer = $fixtureFactory->createByCode('customer', ['dataset' => 'default']);
        $this->customer->persist();
        return [
            'customer' => $this->customer,
        ];
    }

    /**
     * Inject customer pages
     *
     * @param CustomerIndex $customerIndex
     * @param CustomerIndexEdit $customerIndexEdit
     * @return void
     */
    public function __inject(CustomerIndex $customerIndex, CustomerIndexEdit $customerIndexEdit)
    {
        $this->customerIndex = $customerIndex;
        $this->customerIndexEdit = $customerIndexEdit;
    }

    /**
     * Create customer balance
     *
     * @param CustomerBalance $customerBalance
     * @return void
     */
    public function test(CustomerBalance $customerBalance)
    {
        $this->customerIndex->open();
        $filter = ['email' => $this->customer->getEmail()];
        $this->customerIndex->getCustomerGridBlock()->searchAndOpen($filter);
        $this->customerIndexEdit->getCustomerBalanceForm()->fill($customerBalance);
        $this->customerIndexEdit->getPageActionsBlock()->save();
    }
}
