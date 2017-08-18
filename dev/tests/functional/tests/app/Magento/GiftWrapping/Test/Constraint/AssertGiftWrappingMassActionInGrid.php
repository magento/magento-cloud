<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftWrappingMassActionInGrid
 * Assert Gift Wrapping availability in Gift Wrapping grid after mass action
 */
class AssertGiftWrappingMassActionInGrid extends AbstractConstraint
{
    /**
     * Assert Gift Wrapping availability in Gift Wrapping grid after mass action
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param array $giftWrappingsToStay
     * @param string $status
     * @param AssertGiftWrappingInGrid $assert
     * @return void
     */
    public function processAssert(
        GiftWrappingIndex $giftWrappingIndexPage,
        $giftWrappingsToStay,
        $status,
        AssertGiftWrappingInGrid $assert
    ) {
        foreach ($giftWrappingsToStay as $giftWrapping) {
            $assert->processAssert($giftWrappingIndexPage, $giftWrapping, $status);
        }
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'All Gift Wrappings are present in grid.';
    }
}
