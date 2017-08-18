<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertProductIsOutOfStockFailMessage
 * Assert that after adding products by sku to cart, out of stock error message appears
 */
class AssertProductIsOutOfStockFailMessage extends AbstractConstraint
{
    /**
     * Out of stock error message
     */
    const ERROR_MESSAGE = 'Availability: Out of stock.';

    /**
     * Assert that out of stock error message is displayed after adding products to cart
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            \PHPUnit_Framework_Assert::assertContains(
                self::ERROR_MESSAGE,
                $checkoutCart->getAdvancedCheckoutCart()->getFailedItemErrorMessage($product),
                'Wrong error message is displayed.'
            );
        }
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Out of stock error message is present after adding products to cart by sku.';
    }
}
