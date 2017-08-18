<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftCardDiscount
 * Assert that sum of gift card discount is equal to passed from dataset in shopping cart
 */
class AssertGiftCardDiscount extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that sum of gift card discount is equal to passed from dataset in shopping cart
     *
     * @param CheckoutCart $checkoutCart
     * @param string $discount
     * @return void
     */
    public function processAssert(
        CheckoutCart $checkoutCart,
        $discount
    ) {
        $checkoutCart->open();
        $checkoutCart->getTotalsBlock()->waitForUpdatedTotals();
        $actualDiscount = $checkoutCart->getGiftCardDiscountBlock()->getGiftCardDiscount();
        \PHPUnit_Framework_Assert::assertEquals(
            $discount,
            $actualDiscount,
            'Gift card discount price is not equal to the price from fixture.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card discount price is equal to the price from fixture.';
    }
}
