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
 * Assert that Gift Wrapping for Items can be found in Shopping Cart Totals block.
 */
class AssertGiftWrappingForItemsInCart extends AbstractConstraint
{
    /**
     * Assert that Gift Wrapping for Items can be found in cart totals.
     *
     * @param CheckoutCart $checkoutCart
     * @param GiftWrapping $giftWrapping
     * @param string $giftWrappingTotal
     * @param array $products
     * @return void
     */
    public function processAssert(
        CheckoutCart $checkoutCart,
        GiftWrapping $giftWrapping,
        $giftWrappingTotal,
        array $products
    ) {
        $checkoutCart->open();
        foreach ($products as $product) {
            $checkoutCart->getGiftWrappingCartBlock()->getCartItem($product)
                ->getItemGiftOptions()->selectGiftWrapping($giftWrapping);
            $checkoutCart->getTotalsBlock()->waitForUpdatedTotals();
        }

        \PHPUnit_Framework_Assert::assertEquals(
            $giftWrappingTotal,
            $checkoutCart->getGiftWrappingTotalsBlock()->getGiftWrappingAmount(),
            'There is no Item Wrapping in Cart Totals block.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping for Items is present in Cart Totals block.';
    }
}
