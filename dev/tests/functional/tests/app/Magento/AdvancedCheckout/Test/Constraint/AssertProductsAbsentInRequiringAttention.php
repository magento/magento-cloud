<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that products are absent in requiring attention block.
 */
class AssertProductsAbsentInRequiringAttention extends AbstractConstraint
{
    /**
     * Assert that products are absent in requiring attention block.
     *
     * @param CheckoutCart $checkoutCart
     * @param array $products
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $products)
    {
        $checkoutCart->open();
        foreach ($products as $product) {
            \PHPUnit_Framework_Assert::assertFalse(
                $checkoutCart->getAdvancedCheckoutCart()->isFailedItemBlockVisible($product),
                'Product ' . $product->getName() . ' is present in requiring attention block.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'All expected products are absent in requiring attention block.';
    }
}
