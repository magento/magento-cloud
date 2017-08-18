<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\GiftWrapping\Test\Page\Adminhtml\GiftWrappingIndex;

/**
 * Class AssertGiftWrappingMassActionNotInGrid
 * Assert that deleted Gift Wrapping can not be found in grid
 */
class AssertGiftWrappingMassActionNotInGrid extends AssertGiftWrappingNotInGrid
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that deleted Gift Wrapping can not be found in grid via: id, design, website_id, status, price
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrapping[] $giftWrapping
     * @return void
     */
    public function processAssert(GiftWrappingIndex $giftWrappingIndexPage, $giftWrapping)
    {
        foreach ($giftWrapping as $item) {
            parent::processAssert($giftWrappingIndexPage, $item);
        }
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping is not present in grid.';
    }
}
