<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\GiftRegistry\TEst\Fixture\GiftRegistryType;
use Magento\GiftRegistry\Test\Page\Adminhtml\GiftRegistryAdminIndex as GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created Gift Registry type can be found at Stores > Gift Registry grid in backend.
 */
class AssertGiftRegistryTypeInGrid extends AbstractConstraint
{
    /**
     * Assert that created Gift Registry type can be found at Stores > Gift Registry grid in backend.
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryType $giftRegistryType
     * @return void
     */
    public function processAssert(GiftRegistryIndex $giftRegistryIndex, GiftRegistryType $giftRegistryType)
    {
        $giftRegistryIndex->open();
        $filter = ['label' => $giftRegistryType->getLabel()];
        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryIndex->getGiftRegistryGrid()->isRowVisible($filter),
            'Gift registry \'' . $giftRegistryType->getLabel() . '\' is not present in grid.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift registry type is present in GiftRegistryType grid.';
    }
}
