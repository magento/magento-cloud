<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that Gift Wrapping for Order can be found in Shopping Cart Totals block.
 */
class AssertGiftWrappingForOrderInCart extends AbstractConstraint
{
    /**
     * Assert that Gift Wrapping for Order can be found in cart totals.
     *
     * @param CheckoutCart $checkoutCart
     * @param GiftWrapping $giftWrapping
     * @param string $giftWrappingTotal
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, GiftWrapping $giftWrapping, $giftWrappingTotal)
    {
        $checkoutCart->open();
        $checkoutCart->getGiftOptionsOrderBlock()->selectGiftWrapping($giftWrapping);

        \PHPUnit_Framework_Assert::assertEquals(
            $giftWrappingTotal,
            $checkoutCart->getGiftWrappingTotalsBlock()->getGiftWrappingAmount(),
            'There is no Order Wrapping in Cart Totals block.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping for Order is present in Cart Totals block.';
    }
}
