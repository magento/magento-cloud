<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertMinRequestedQtyFailMessage
 * Assert that after adding products by sku to cart, requested quantity is less than allowed error message appears
 */
class AssertMinRequestedQtyFailMessage extends AbstractConstraint
{
    /**
     * Error requested quantity message
     */
    const ERROR_QUANTITY_MESSAGE = 'You can\'t add this many to your cart.';

    /**
     * Error minimum quantity allowed message
     */
    const ERROR_MINIMUM_QUANTITY_MESSAGE = 'You must buy at least %d of these per purchase.';

    /**
     * Assert that requested quantity is less than allowed error message is displayed after adding products to cart
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $requiredAttentionProducts)
    {
        foreach ($requiredAttentionProducts as $product) {
            $currentMessage = $checkoutCart->getAdvancedCheckoutCart()->getFailedItemErrorMessage($product);
            \PHPUnit_Framework_Assert::assertContains(
                self::ERROR_QUANTITY_MESSAGE,
                $currentMessage,
                'Wrong error message is displayed.'
            );
            \PHPUnit_Framework_Assert::assertContains(
                sprintf(self::ERROR_MINIMUM_QUANTITY_MESSAGE, $product->getData('stock_data')['min_sale_qty']),
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
        return 'Requested quantity is less than allowed error message is present after adding products to cart.';
    }
}
