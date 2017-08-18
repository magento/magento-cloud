<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\Test\Page\GiftRegistryItems;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryItemsUpdatedFrontendSuccessMessage
 * Assert that after update a Gift Registry items successful message appears
 */
class AssertGiftRegistryItemsUpdatedFrontendSuccessMessage extends AbstractConstraint
{
    /**
     * Success gift registry items update message
     */
    const SUCCESS_MESSAGE = 'You updated the gift registry items.';

    /**
     * Assert that success message is displayed after gift registry items has been updated
     *
     * @param GiftRegistryItems $giftRegistryItems
     * @return void
     */
    public function processAssert(GiftRegistryItems $giftRegistryItems)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $giftRegistryItems->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry items success update message is present.';
    }
}
