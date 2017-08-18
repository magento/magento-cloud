<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Reward\Test\Page\Adminhtml\RewardRateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertRewardPointsSuccessDeleteMessage
 * Asserts that success delete message equals to expected message.
 */
class AssertRewardPointsSuccessDeleteMessage extends AbstractConstraint
{
    /**
     * Message about successful deletion reward exchange rate
     */
    const SUCCESS_DELETE_MESSAGE = 'You deleted the rate.';

    /**
     * Asserts that success delete message equals to expected message
     *
     * @param RewardRateIndex $rewardRateIndex
     * @return void
     */
    public function processAssert(RewardRateIndex $rewardRateIndex)
    {
        $actualMessage = $rewardRateIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_DELETE_MESSAGE,
            $actualMessage,
            'Wrong delete message is displayed.'
        );
    }

    /**
     * Returns message if delete message equals to expected message
     *
     * @return string
     */
    public function toString()
    {
        return 'Success delete message on Reward Exchange Rates page is correct.';
    }
}
