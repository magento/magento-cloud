<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestStep;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Apply reward points to customer.
 */
class ApplyRewardPointsToCustomerStep implements TestStepInterface
{
    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Reward points amount.
     *
     * @var string
     */
    protected $rewardPoints;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param Customer $customer
     * @param string|null $rewardPoints
     */
    public function __construct(FixtureFactory $fixtureFactory, Customer $customer, $rewardPoints = null)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->rewardPoints = $rewardPoints;
        $this->customer = $customer;
    }

    /**
     * Apply reward points to customer.
     *
     * @return void
     */
    public function run()
    {
        if ($this->rewardPoints !== null) {
            $reward = $this->fixtureFactory->createByCode(
                'reward',
                [
                    'dataset' => $this->rewardPoints,
                    'data' => [
                        'customer_id' => ['customer' => $this->customer],
                    ]
                ]
            );
            $reward->persist();
        }
    }
}
