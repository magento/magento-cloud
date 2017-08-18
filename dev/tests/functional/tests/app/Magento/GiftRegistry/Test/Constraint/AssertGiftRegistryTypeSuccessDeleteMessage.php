<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryTypeSuccessDeleteMessage
 * Assert that success delete message is displayed after gift registry has been deleted
 */
class AssertGiftRegistryTypeSuccessDeleteMessage extends AbstractConstraint
{
    /**
     * Success gift registry delete message
     */
    const DELETE_MESSAGE = 'You deleted the gift registry type.';

    /**
     * Assert that success delete message is displayed after gift registry has been deleted
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @return void
     */
    public function processAssert(GiftRegistryIndex $giftRegistryIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::DELETE_MESSAGE,
            $giftRegistryIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong delete message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry type success delete message is present.';
    }
}
