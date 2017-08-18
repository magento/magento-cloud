<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\TestStep;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Apply customer balance to customer.
 */
class ApplyCustomerBalanceToCustomerStep implements TestStepInterface
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Customer Balance amount.
     *
     * @var string
     */
    protected $customerBalance;

    /**
     * @param FixtureFactory $fixtureFactory
     * @param Customer $customer
     * @param string|null $customerBalance
     */
    public function __construct(FixtureFactory $fixtureFactory, Customer $customer, $customerBalance = null)
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->customerBalance = $customerBalance;
        $this->customer = $customer;
    }

    /**
     * Apply customer balance to customer.
     *
     * @return array|null
     */
    public function run()
    {
        if ($this->customerBalance === null) {
            return null;
        }
        $customerBalance = $this->fixtureFactory->createByCode(
            'customerBalance',
            [
                'dataset' => $this->customerBalance,
                'data' => [
                    'customer_id' => ['customer' => $this->customer],
                ]
            ]
        );
        $customerBalance->persist();

        return ['customerBalance' => $customerBalance];
    }
}
