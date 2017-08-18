<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertProductConfiguringFromRequiringAttention
 * Assert that product can be configured and added to cart after added this product to cart by sku
 */
class AssertProductConfiguringFromRequiringAttention extends AbstractConstraint
{
    /**
     * Success adding product to cart message
     */
    const SUCCESS_MESSAGE = 'You added %s to your shopping cart.';

    /**
     * Assert that product can be configured and added to cart after added this product to cart by sku
     *
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductView $catalogProductView
     * @param array $requiredAttentionProducts
     * @return void
     */
    public function processAssert(
        CheckoutCart $checkoutCart,
        CatalogProductView $catalogProductView,
        array $requiredAttentionProducts
    ) {
        foreach ($requiredAttentionProducts as $product) {
            $checkoutCart->open()->getAdvancedCheckoutCart()->clickSpecifyProductOptionsLink($product);
            $catalogProductView->getViewBlock()->addToCart($product);
            \PHPUnit_Framework_Assert::assertEquals(
                sprintf(self::SUCCESS_MESSAGE, $product->getName()),
                $checkoutCart->getMessagesBlock()->getSuccessMessage()
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
        return "Product can be configured from requiring attention block.";
    }
}
