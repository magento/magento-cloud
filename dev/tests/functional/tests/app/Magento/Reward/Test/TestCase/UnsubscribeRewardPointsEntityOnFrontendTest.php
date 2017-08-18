<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\Reward\Test\Fixture\Reward;
use Magento\Reward\Test\Page\RewardCustomerInfo;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create customer.
 *
 * Steps:
 * 1. Login to frontend as customer created in preconditions.
 * 2. Open My Accounts > Rewards Points.
 * 3. Fill data according to dataset.
 * 4. Perform all asserts.
 *
 * @group Product_Attributes, Reward_Points
 * @ZephyrId MAGETWO-26381
 */
class UnsubscribeRewardPointsEntityOnFrontendTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * Customer account information page.
     *
     * @var RewardCustomerInfo
     */
    protected $rewardCustomerInfo;

    /**
     * Preparing magento instance for whole test.
     */
    public function __prepare()
    {
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
    }

    /**
     * Injection data.
     *
     * @param RewardCustomerInfo $rewardCustomerInfo
     * @return void
     */
    public function __inject(RewardCustomerInfo $rewardCustomerInfo)
    {
        $this->rewardCustomerInfo = $rewardCustomerInfo;
    }

    /**
     * Unsubscribe frontend reward points entity test.
     *
     * @param Customer $customer
     * @param Reward $reward
     * @param CustomerAccountCreate $customerAccountCreate
     */
    public function test(Customer $customer, Reward $reward, CustomerAccountCreate $customerAccountCreate)
    {
        // Precondition
        $customerAccountCreate->open();
        $customerAccountCreate->getRegisterForm()->registerCustomer($customer);

        // Steps
        $this->rewardCustomerInfo->getAccountMenuBlock()->openMenuItem('Reward Points');
        $this->rewardCustomerInfo->getRewardPointsBlock()->updateSubscription($reward);
    }

    /**
     * Clear magento instance after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
    }
}
