<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Page\Adminhtml\BannerIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerDeleteMessage
 * Assert that success delete message is appeared after banner has been deleted
 */
class AssertBannerDeleteMessage extends AbstractConstraint
{
    const SUCCESS_DELETE_MESSAGE = 'You deleted the banner.';

    /**
     * Assert that success delete message is appeared after banner has been deleted
     *
     * @param BannerIndex $bannerIndex
     * @return void
     */
    public function processAssert(BannerIndex $bannerIndex)
    {
        $actualMessage = $bannerIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_DELETE_MESSAGE,
            $actualMessage,
            'Wrong delete message is displayed.'
            . "\nExpected: " . self::SUCCESS_DELETE_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Delete message is displayed.';
    }
}
