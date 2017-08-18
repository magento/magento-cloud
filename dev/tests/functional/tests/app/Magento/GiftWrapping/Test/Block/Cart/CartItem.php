<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Cart;

/**
 * Product item block on Shopping Cart page.
 */
class CartItem extends \Magento\Checkout\Test\Block\Cart\CartItem
{
    /**
     * Gift Options block.
     *
     * @var string
     */
    private $giftOptions = '.gift-options-cart-item';

    /**
     * Get Gift Options block.
     *
     * @return \Magento\GiftWrapping\Test\Block\Cart\Item\GiftOptions
     */
    public function getItemGiftOptions()
    {
        $itemGiftOptions = $this->_rootElement->waitUntil(
            function () {
                $element = $this->_rootElement->find($this->giftOptions);
                return $element->isVisible() ? $element : null;
            }
        );

        return $this->blockFactory->create(
            \Magento\GiftWrapping\Test\Block\Cart\Item\GiftOptions::class,
            ['element' => $itemGiftOptions]
        );
    }
}
