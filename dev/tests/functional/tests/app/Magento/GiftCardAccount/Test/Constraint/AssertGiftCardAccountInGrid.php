<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\Page\Adminhtml\Index;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that gift card account in grid.
 */
class AssertGiftCardAccountInGrid extends AbstractConstraint
{
    /**
     * Assert that gift card account in grid.
     *
     * @param GiftCardAccount $giftCardAccount
     * @param Index $index
     * @return void
     */
    public function processAssert(GiftCardAccount $giftCardAccount, Index $index)
    {
        $index->open();
        if ($giftCardAccount->hasData('date_expires')) {
            $dateExpires = date("M j, Y", strtotime($giftCardAccount->getDateExpires()));
        } else {
            $dateExpires = '--';
        }
        $balance = $giftCardAccount->getBalance();
        $filter = [
            'balance' => $balance,
            'date_expires' => $dateExpires,
        ];
        \PHPUnit_Framework_Assert::assertTrue(
            $index->getGiftCardAccount()->isRowVisible($filter, false, false),
            "Gift card with balance = '$balance' and expiration date = '$dateExpires' is absent in "
            . "gift card account grid."
        );
    }

    /**
     * Success assert of  gift card account in grid.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer gift card account in grid.';
    }
}
