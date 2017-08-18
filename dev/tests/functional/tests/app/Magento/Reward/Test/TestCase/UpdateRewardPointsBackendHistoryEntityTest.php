<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\Reward\Test\Fixture\Reward;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create customer.
 *
 * Steps:
 * 1. Open backend.
 * 2. Open created customer in preconditions.
 * 3. Fill data from dataset.
 * 4. Click Save and Continue.
 * 5. Perform all assertions.
 *
 * @group Reward_Points
 * @ZephyrId MAGETWO-26683
 */
class UpdateRewardPointsBackendHistoryEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const SEVERITY = 'S3';
    /* end tags */

    /**
     * CustomerIndex page.
     *
     * @var CustomerIndex
     */
    protected $customerIndex;

    /**
     * CustomerEdit page.
     *
     * @var CustomerIndexEdit
     */
    protected $customerIndexEdit;

    /**
     * Preconditions for test.
     *
     * @param Customer $customer
     * @return array
     */
    public function __prepare(Customer $customer)
    {
        $customer->persist();
        return ['customer' => $customer];
    }

    /**
     * Page injection for test.
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
     * Run Test Creation for Backend History RewardPointsEntity.
     *
     * @param Customer $customer
     * @param Reward $reward
     * @return void
     */
    public function test(Customer $customer, Reward $reward)
    {
        $filter = ['email' => $customer->getEmail()];

        // Steps
        $this->customerIndex->open();
        $this->customerIndex->getCustomerGridBlock()->searchAndOpen($filter);
        $this->customerIndexEdit->getCustomerForm()->fill($reward);
        $this->customerIndexEdit->getPageActionsBlock()->save();
    }
}
