<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingNew;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that mass action Gift Wrapping form was filled correctly.
 */
class AssertGiftWrappingMassActionForm extends AbstractConstraint
{
    /**
     * Assert that mass action Gift Wrapping form was filled correctly.
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrappingNew $giftWrappingNewPage
     * @param array $giftWrapping
     * @param string $status
     * @param AssertGiftWrappingForm $assert
     * @return void
     */
    public function processAssert(
        GiftWrappingIndex $giftWrappingIndexPage,
        GiftWrappingNew $giftWrappingNewPage,
        array $giftWrapping,
        $status,
        AssertGiftWrappingForm $assert
    ) {
        foreach ($giftWrapping as $item) {
            $assert->processAssert($giftWrappingIndexPage, $giftWrappingNewPage, $item, $status);
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'All Gift Wrapping forms were filled correctly.';
    }
}
