<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\TEst\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryInGrid
 * Assert that gift registry is present in grid
 */
class AssertGiftRegistryInGrid extends AbstractConstraint
{
    /**
     * Assert that created Gift Registry can be found at Gift Registry grid by title
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistry $giftRegistry
     * @return void
     */
    public function processAssert(GiftRegistryIndex $giftRegistryIndex, GiftRegistry $giftRegistry)
    {
        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryIndex->open()->getGiftRegistryGrid()->isGiftRegistryInGrid($giftRegistry),
            'Gift registry \'' . $giftRegistry->getTitle() . '\' is not present in grid.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry is present in grid.';
    }
}
