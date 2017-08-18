<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\Test\Page\GiftRegistryItems;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryIsEmptyMessage
 * Assert that notice message appears if Gift Registry doesn't have any items
 */
class AssertGiftRegistryIsEmptyMessage extends AbstractConstraint
{
    /**
     * Gift registry info message
     */
    const INFO_MESSAGE = 'This gift registry has no items.';

    /**
     * Assert that notice message appears if Gift Registry doesn't have any items after delete
     *
     * @param GiftRegistryItems $giftRegistryItems
     * @return void
     */
    public function processAssert(GiftRegistryItems $giftRegistryItems)
    {
        $giftRegistryItems->open();
        \PHPUnit_Framework_Assert::assertEquals(
            self::INFO_MESSAGE,
            $giftRegistryItems->getGiftRegistryItemsBlock()->getInfoMessage(),
            'Wrong notice message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry has no items message is present after gift registry all items have been deleted.';
    }
}
