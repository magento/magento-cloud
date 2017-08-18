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
 * Class AssertGiftWrappingInGrid
 */
class AssertGiftWrappingInGrid extends AbstractConstraint
{
    /**
     * Assert Gift Wrapping availability in Gift Wrapping grid
     *
     * @param GiftWrappingIndex $giftWrappingIndexPage
     * @param GiftWrapping $giftWrapping
     * @param string $status
     * @param GiftWrapping $initialGiftWrapping
     * @return void
     */
    public function processAssert(
        GiftWrappingIndex $giftWrappingIndexPage,
        GiftWrapping $giftWrapping,
        $status = '-',
        GiftWrapping $initialGiftWrapping = null
    ) {
        $data = ($initialGiftWrapping !== null)
            ? array_merge($initialGiftWrapping->getData(), $giftWrapping->getData())
            : $giftWrapping->getData();
        reset($data['website_ids']);
        $filter = [
            'design' => $data['design'],
            'status' => $status === '-' ? $data['status'] : $status,
            'website_ids' => current($data['website_ids']),
            'base_price' => $data['base_price'],
        ];

        $giftWrappingIndexPage->open();
        \PHPUnit_Framework_Assert::assertTrue(
            $giftWrappingIndexPage->getGiftWrappingGrid()->isRowVisible($filter, true, false),
            'Gift Wrapping \'' . $filter['design'] . '\' is absent in Gift Wrapping grid.'
        );
    }

    /**
     * Text of Gift Wrapping in grid assert
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping is present in grid.';
    }
}
