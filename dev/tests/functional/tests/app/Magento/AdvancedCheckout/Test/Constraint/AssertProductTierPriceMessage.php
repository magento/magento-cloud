<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertProductTierPriceMessage
 * Assert that product has tier price message appears after adding products by sku to shopping cart
 */
class AssertProductTierPriceMessage extends AbstractConstraint
{
    /**
     * Assert that product has tier price message appears after adding products by sku to shopping cart
     *
     * @param CheckoutCart $checkoutCart
     * @param array $requiredAttentionProducts
     * @param array $orderOptions
     * @return void
     */
    public function processAssert(CheckoutCart $checkoutCart, array $requiredAttentionProducts, array $orderOptions)
    {
        foreach ($requiredAttentionProducts as $product) {
            $messages = $checkoutCart->getAdvancedCheckoutCart()->getTierPriceMessages($product);
            $tierPrices = $product->getTierPrice();
            $applicableTierPrices = [];
            $productPrices = [];
            foreach ($tierPrices as $tierPrice) {
                if ($tierPrice['price_qty'] > $orderOptions['qty']) {
                    $applicableTierPrices[] = $tierPrice;
                } else {
                    $productPrices[] = $tierPrice['price'];
                }
            }
            $productPrice = max($productPrices);
            \PHPUnit_Framework_Assert::assertTrue(
                count($messages) === count($applicableTierPrices),
                'Wrong qty messages is displayed.'
            );
            foreach ($applicableTierPrices as $key => $tierPrice) {
                $price = (bool)strpos($messages[$key], (string)$tierPrice['price']);
                $priceQty = (bool)strpos($messages[$key], (string)$tierPrice['price_qty']);
                $savePercent = (bool)strpos($messages[$key], $this->getSavePercent($productPrice, $tierPrice));
                \PHPUnit_Framework_Assert::assertTrue(
                    $price and $priceQty and $savePercent,
                    'Wrong message is displayed.'
                );
            }
        }
    }

    /**
     * Get save percent
     *
     * @param string $price
     * @param array $tierPrice
     * @return string
     */
    protected function getSavePercent($price, array $tierPrice)
    {
        $tierPriceQty = $tierPrice['price_qty'];

        return round(100 - ((100 * ($tierPrice['price'] * $tierPriceQty)) / ($price * $tierPriceQty))) . "%";
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Product has tier price message is present after adding products to cart.';
    }
}
