<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountCreate;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Reward\Test\Fixture\RewardRate;
use Magento\Reward\Test\Page\RewardCustomerInfo;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that reward points messages appears.
 */
class AssertRewardPointsBalance extends AbstractConstraint
{
    /**
     * Messages about reward points balance.
     */
    const REWARD_POINTS_BALANCE = 'Your balance is %d Reward points.';

    /**
     * Message about reward points exchange rate.
     */
    const REWARD_POINTS_EXCHANGE_RATE = 'Current exchange rates: %d Reward points = $%.2f';

    /**
     * Assert that "Your balance is X Reward points ($X.00)." and current exchange message are appeared
     * on the Customer Dashboard page on Reward point tab.
     *
     * @param Customer $customer
     * @param CustomerAccountCreate $customerAccountCreate
     * @param CustomerAccountIndex $customerAccountIndex
     * @param RewardCustomerInfo $rewardCustomerInfo
     * @param RewardRate $rate
     * @param string $registrationReward
     * @param RewardRate $updateRate
     */
    public function processAssert(
        Customer $customer,
        CustomerAccountCreate $customerAccountCreate,
        CustomerAccountIndex $customerAccountIndex,
        RewardCustomerInfo $rewardCustomerInfo,
        RewardRate $rate,
        $registrationReward,
        RewardRate $updateRate = null
    ) {
        $rate = $updateRate === null ? $rate : $updateRate;

        $customerAccountCreate->open()->getRegisterForm()->registerCustomer($customer);
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Reward Points');

        $rewardPointsBlock = $rewardCustomerInfo->getRewardPointsBlock();
        $actual['reward_points'] = $rewardPointsBlock->getRewardPointsBalance();
        $actual['exchange_rate'] = $rewardPointsBlock->getRewardRatesMessages();
        $expected['reward_points'] = sprintf(self::REWARD_POINTS_BALANCE, $registrationReward);
        $expected['exchange_rate'] = sprintf(
            self::REWARD_POINTS_EXCHANGE_RATE,
            $rate->getValue(),
            $rate->getEqualValue()
        );

        \PHPUnit_Framework_Assert::assertEquals($expected, $actual, 'Wrong success messages are displayed.');
    }

    /**
     * Returns a string representation of successful assertion
     *
     * @return string
     */
    public function toString()
    {
        return 'Reward points balance and exchange rate in the user account are displayed correctly.';
    }
}
