<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Constraint;

use Magento\GiftCard\Test\Fixture\GiftCardProduct;
use Magento\Wishlist\Test\Constraint\AssertProductInCustomerWishlistOnBackendGrid;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Class AssertGiftCardProductInCustomerWishlistOnBackendGrid
 * Assert that gift card product is present in grid on customer's wish list tab with configure option and qty
 */
class AssertGiftCardProductInCustomerWishlistOnBackendGrid extends AssertProductInCustomerWishlistOnBackendGrid
{
    /**
     * Prepare options
     *
     * @param FixtureInterface $product
     * @return array
     */
    protected function prepareOptions(FixtureInterface $product)
    {
        /** @var GiftCardProduct $product */
        $productOptions = parent::prepareOptions($product);
        $checkoutData = $product->getCheckoutData()['options'];
        if (isset($checkoutData['giftcard_options'])) {
            $productOptions += [
                [
                    'option_name' => 'Gift Card Sender',
                    'value' => $checkoutData['giftcard_options']['giftcard_sender_name'],
                ],
                [
                    'option_name' => 'Gift Card Recipient',
                    'value' => $checkoutData['giftcard_options']['giftcard_recipient_name']
                ],
                [
                    'option_name' => 'Gift Card Message',
                    'value' => $checkoutData['giftcard_options']['giftcard_message']
                ]
            ];
        }

        return $productOptions;
    }
}
