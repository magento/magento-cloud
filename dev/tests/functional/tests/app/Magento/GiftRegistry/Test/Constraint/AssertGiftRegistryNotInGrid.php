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
 * Class AssertGiftRegistryNotInGrid
 * Assert that gift registry is absent in grid
 */
class AssertGiftRegistryNotInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that created Gift Registry can not be found at Gift Registry grid by title
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistry $giftRegistry
     * @return void
     */
    public function processAssert(GiftRegistryIndex $giftRegistryIndex, GiftRegistry $giftRegistry)
    {
        \PHPUnit_Framework_Assert::assertFalse(
            $giftRegistryIndex->open()->getGiftRegistryGrid()->isGiftRegistryInGrid($giftRegistry),
            'Gift registry \'' . $giftRegistry->getTitle() . '\' is present in grid.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry is absent in grid.';
    }
}
