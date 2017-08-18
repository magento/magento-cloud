<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertProductsAddBySkuSuccessMessage
 * Assert that after adding products by sku to shopping cart, successful message appears
 */
class AssertProductsAddBySkuSuccessMessage extends AbstractConstraint
{
    /**
     * Success adding products to shopping cart message
     */
    const SUCCESS_MESSAGE = 'You added %d %s to your shopping cart.';

    /**
     * Assert that success message is displayed after adding products by sku to shopping cart
     *
     * @param CheckoutCart $checkoutCart
     * @param array $products
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $products)
    {
        $productsQty = count($products);
        $qtyString = ($productsQty > 1) ? 'products' : 'product';
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, $productsQty, $qtyString),
            $checkoutCart->getMessagesBlock()->getSuccessMessage()
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Adding products by sku to shopping cart success message is present.';
    }
}
