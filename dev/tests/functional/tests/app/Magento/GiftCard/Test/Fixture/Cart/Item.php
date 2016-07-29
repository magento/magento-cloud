<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCard\Test\Fixture\Cart;

use Magento\GiftCard\Test\Fixture\GiftCardProduct;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Data for verify cart item block on checkout page.
 *
 * Data keys:
 *  - product (fixture data for verify)
 */
class Item extends \Magento\Catalog\Test\Fixture\Cart\Item
{
    /**
     * @constructor
     * @param FixtureInterface $product
     */
    public function __construct(FixtureInterface $product)
    {
        parent::__construct($product);

        /** @var GiftCardProduct $product */
        $checkoutData = $product->getCheckoutData();
        $optionsData = $checkoutData['options']['giftcard_options'];
        $cartItemOptions = [
            [
                'title' => 'Gift Card Sender',
                'value' => "{$optionsData['giftcard_sender_name']} <{$optionsData['giftcard_sender_email']}>",
            ],
            [
                'title' => 'Gift Card Recipient',
                'value' => "{$optionsData['giftcard_recipient_name']} <{$optionsData['giftcard_recipient_email']}>"
            ],
            [
                'title' => 'Gift Card Message',
                'value' => "{$optionsData['giftcard_message']}"
            ],
        ];

        $this->data['options'] = isset($this->data['options'])
            ? $this->data['options'] + $cartItemOptions
            : $cartItemOptions;
    }
}
