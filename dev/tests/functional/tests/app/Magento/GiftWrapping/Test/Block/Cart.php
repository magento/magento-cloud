<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block;

/**
 * Shopping Cart block.
 */
class Cart extends \Magento\Checkout\Test\Block\Cart
{
    /**
     * Cart item class name.
     *
     * @var string
     */
    protected $cartItemClass = \Magento\GiftWrapping\Test\Block\Cart\CartItem::class;
}
