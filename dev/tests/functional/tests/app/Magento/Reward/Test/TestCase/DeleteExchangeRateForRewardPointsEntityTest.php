<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\Reward\Test\Fixture\RewardRate;
use Magento\Reward\Test\Page\Adminhtml\RewardRateIndex;
use Magento\Reward\Test\Page\Adminhtml\RewardRateNew;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Exchange rate (Points > Currency) is created.
 * 2. Exchange rate (Currency > Points) is created.
 *
 * Steps:
 * 1. Login to the backend.
 * 2. Navigate to Stores > Other Settings > Reward Exchange Rates
 * 3. Click on the exchange rate from preconditions.
 * 4. Click on the "Delete" button
 * 5. Perform appropriate assertions.
 *
 * @group Reward_Points
 * @ZephyrId MAGETWO-26344
 */
class DeleteExchangeRateForRewardPointsEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * Reward Rate Index page.
     *
     * @var RewardRateIndex
     */
    protected $rewardRateIndex;

    /**
     * Reward Rate New page.
     *
     * @var RewardRateNew
     */
    protected $rewardRateNew;

    /**
     * Preparing magento instance for whole test.
     *
     * @param FixtureFactory $fixtureFactory
     * @param CustomerAccountCreate $customerAccountCreate
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory, CustomerAccountCreate $customerAccountCreate)
    {
        $configuration = $fixtureFactory->createByCode('configData', ['dataset' => 'reward_purchase']);
        $customer = $fixtureFactory->create(
            \Magento\Customer\Test\Fixture\Customer::class,
            ['dataset' => 'register_customer']
        );

        $configuration->persist();
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
        $customerAccountCreate->open()->getRegisterForm()->registerCustomer($customer);

        return ['customer' => $customer];
    }

    /**
     * Preparing magento instance for test variation.
     *
     * @param RewardRateIndex $rewardRateIndex
     * @param RewardRateNew $rewardRateNew
     * @return void
     */
    public function __inject(RewardRateIndex $rewardRateIndex, RewardRateNew $rewardRateNew)
    {
        $this->rewardRateIndex = $rewardRateIndex;
        $this->rewardRateNew = $rewardRateNew;
    }

    /**
     * Run Test Creation for Exchange Rate Deletion for RewardRateEntity.
     *
     * @param RewardRate $rate
     * @return void
     */
    public function test(RewardRate $rate)
    {
        // Preconditions
        $rate->persist();

        // Steps
        $filter = ['rate_id' => $rate->getRateId()];
        $this->rewardRateIndex->open();
        $this->rewardRateIndex->getRewardRateGrid()->searchAndOpen($filter);
        $this->rewardRateNew->getFormPageActions()->delete();
        $this->rewardRateNew->getModalBlock()->acceptAlert();
    }

    /**
     * Clear magento instance after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\Reward\Test\TestStep\DeleteAllRewardRatesStep::class)->run();
    }
}
