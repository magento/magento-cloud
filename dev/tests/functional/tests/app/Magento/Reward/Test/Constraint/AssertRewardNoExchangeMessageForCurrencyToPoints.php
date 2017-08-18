<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Reward\Test\Fixture\RewardRate;
use Magento\Reward\Test\Page\RewardCustomerInfo;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that "Each $X spent will earn X Reward points" message is not displayed on the RewardCustomerInfo page.
 */
class AssertRewardNoExchangeMessageForCurrencyToPoints extends AbstractConstraint
{
    /**
     * Assert that "Each $X spent will earn X Reward points" message is not displayed on the RewardCustomerInfo page.
     *
     * @param CustomerAccountIndex $customerAccountIndex
     * @param RewardCustomerInfo $rewardCustomerInfo
     * @param RewardRate $rate
     * @return void
     */
    public function processAssert(
        CustomerAccountIndex $customerAccountIndex,
        RewardCustomerInfo $rewardCustomerInfo,
        RewardRate $rate
    ) {
        $customerAccountIndex->open()->getAccountMenuBlock()->openMenuItem('Reward Points');
        $actualInformation = $rewardCustomerInfo->getRewardPointsBlock()->getRewardPointsBalance();
        $expectedMessage = sprintf(
            'Each $%s spent will earn %d Reward points.',
            $rate->getValue(),
            $rate->getEqualValue()
        );

        \PHPUnit_Framework_Assert::assertFalse(
            strpos($actualInformation, $expectedMessage),
            $expectedMessage . ' is displayed on the RewardCustomerInfo page.'
        );
    }

    /**
     * Returns string representation of assert.
     *
     * @return string
     */
    public function toString()
    {
        return 'Each $X spent will earn X Reward points message is not displayed on the RewardCustomerInfo page.';
    }
}
