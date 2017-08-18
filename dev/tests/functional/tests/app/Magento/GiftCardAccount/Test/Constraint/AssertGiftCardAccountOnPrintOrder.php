<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\Sales\Test\Page\SalesGuestPrint;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that gift card amount printed correctly on sales guest print page.
 */
class AssertGiftCardAccountOnPrintOrder extends AbstractConstraint
{
    /**
     * Assert that gift card amount printed correctly on sales guest print page.
     *
     * @param SalesGuestPrint $salesGuestPrint
     * @param GiftCardAccount $giftCardAccount
     * @return void
     */
    public function processAssert(SalesGuestPrint $salesGuestPrint, GiftCardAccount $giftCardAccount)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            number_format($giftCardAccount->getBalance(), 2),
            $salesGuestPrint->getViewGiftCard()->getItemBlock()->getGiftCardDiscount()
        );
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return "Gift card amount was printed correctly.";
    }
}
