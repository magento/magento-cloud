<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftWrappingMassActionSuccessDeleteMessage
 * Assert that after mass delete Gift Wrapping successful message appears
 */
class AssertGiftWrappingMassActionSuccessDeleteMessage extends AbstractConstraint
{
    /**
     * Message displayed after delete gift wrapping
     */
    const SUCCESS_DELETE_MESSAGE = 'You deleted a total of %d records.';

    /**
     * Assert that after mass delete Gift Wrapping successful message appears
     *
     * @param GiftWrappingIndex $giftWrappingIndex
     * @param string $number
     * @return void
     */
    public function processAssert(GiftWrappingIndex $giftWrappingIndex, $number)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_DELETE_MESSAGE, $number),
            $giftWrappingIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping success mass delete message is present.';
    }
}
