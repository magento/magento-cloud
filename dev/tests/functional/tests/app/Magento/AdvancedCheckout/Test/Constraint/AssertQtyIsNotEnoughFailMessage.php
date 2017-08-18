<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertQtyIsNotEnoughFailMessage
 * Assert that after adding products by sku to shopping cart, requested quantity is not available error message appears
 */
class AssertQtyIsNotEnoughFailMessage extends AbstractConstraint
{
    /**
     * Requested quantity is not available error message
     */
    const ERROR_QUANTITY_MESSAGE = 'We don\'t have as many of these as you want.';

    /**
     * Quantity left in stock error message
     */
    const LEFT_IN_STOCK_ERROR_MESSAGE = 'Only %d left in stock';

    /**
     * Assert that requested quantity is not available error message is displayed after adding products by sku to cart
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            $currentMessage = $checkoutCart->getAdvancedCheckoutCart()->getFailedItemErrorMessage($product);
            \PHPUnit_Framework_Assert::assertContains(
                self::ERROR_QUANTITY_MESSAGE,
                $currentMessage,
                'Wrong error message is displayed.'
            );
            $productQty = $product->getQuantityAndStockStatus()['qty'];
            \PHPUnit_Framework_Assert::assertContains(
                sprintf(self::LEFT_IN_STOCK_ERROR_MESSAGE, $productQty),
                $currentMessage,
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
        return 'Requested quantity is not available error message is present after adding products to shopping cart.';
    }
}
