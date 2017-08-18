<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Product;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Mtf\Client\Locator;

/**
 * Class View
 * Product Multiple Wish list view block on the product page
 */
class View extends \Magento\Catalog\Test\Block\Product\View
{
    /**
     * Add to Multiple Wishlist button
     *
     * @var string
     */
    protected $addToMultipleWishlist = '.wishlist [data-toggle="dropdown"]';

    /**
     * Item wish list
     *
     * @var string
     */
    protected $wishlistItem = '//*[@data-action="add-to-wishlist" and @title = "%s"]';

    /**
     * Select which Wishlist you want to add product to
     *
     * @param MultipleWishlist $multipleWishlist
     * @return void
     */
    public function addToMultipleWishlist(MultipleWishlist $multipleWishlist)
    {
        $this->_rootElement->find($this->addToMultipleWishlist)->click();
        $this->_rootElement->find(
            sprintf($this->wishlistItem, $multipleWishlist->getName()),
            Locator::SELECTOR_XPATH
        )->click();
    }
}
