<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\GiftCardAccount\Test\Page\Adminhtml\Index;
use Magento\GiftCardAccount\Test\Page\Adminhtml\NewIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that gift card account balance matches expected.
 */
class AssertGiftCardAccountBalance extends AbstractConstraint
{
    /**
     * Assert that gift card account balance matches expected.
     *
     * @param Index $giftCardAccountIndex
     * @param NewIndex $giftCardAccountEdit
     * @param GiftCardAccount $giftCardAccount
     * @param float $balance
     * @return void
     */
    public function processAssert(
        Index $giftCardAccountIndex,
        NewIndex $giftCardAccountEdit,
        GiftCardAccount $giftCardAccount,
        $balance = 0.00
    ) {
        $giftCardAccountIndex->open();
        $giftCardAccountIndex->getGiftCardAccount()->searchAndOpen(
            ['code' => $giftCardAccount->getCode()]
        );

        $data = $giftCardAccountEdit->getPageMainForm()->getData();
        $actualBalance = filter_var(
            $data['balance'],
            FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION
        );

        \PHPUnit_Framework_Assert::assertEquals(
            $balance,
            $actualBalance,
            sprintf(
                "Gift card account balance %0.2f mismatching %0.2f.",
                $actualBalance,
                $balance
            )
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card account balance matches expected.';
    }
}
