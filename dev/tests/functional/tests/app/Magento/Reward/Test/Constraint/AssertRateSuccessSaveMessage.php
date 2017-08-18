<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Constraint;

use Magento\Reward\Test\Page\Adminhtml\RewardRateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertRateSuccessSaveMessage
 * Assert that message is present on page
 */
class AssertRateSuccessSaveMessage extends AbstractConstraint
{
    /**
     * Message after success saved Exchange Rate
     */
    const SUCCESS_SAVE_MESSAGE = 'You saved the rate.';

    /**
     * Assert that specified message is present on page
     *
     * @param RewardRateIndex $gridPage
     * @return void
     */
    public function processAssert(RewardRateIndex $gridPage)
    {
        $actualMessage = $gridPage->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_SAVE_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of successful assertion
     *
     * @return string
     */
    public function toString()
    {
        return 'Reward points success create message is present and equals to expected.';
    }
}
