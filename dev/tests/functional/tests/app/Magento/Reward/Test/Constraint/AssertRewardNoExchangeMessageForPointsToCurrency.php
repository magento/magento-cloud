<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Reward\Test\Fixture\RewardRate;
use Magento\Reward\Test\Page\RewardCustomerInfo;

/**
 * Assert that "Each X Reward points can be redeemed for $X." message is not displayed on the RewardCustomerInfo page.
 */
class AssertRewardNoExchangeMessageForPointsToCurrency extends AbstractConstraint
{
    /**
     * Assert that Each X Reward points can be redeemed for $X message is not displayed on the RewardCustomerInfo page.
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
            'Each %d Reward points can be redeemed for $%s.',
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
        return 'Each X Reward points can be redeemed for $X message is not displayed on the RewardCustomerInfo page.';
    }
}
