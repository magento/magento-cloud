<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Adminhtml\Sales\Order\Create\Sidebar;

use Magento\Sales\Test\Block\Adminhtml\Order\Create\CustomerActivities\Sidebar;
use Magento\Mtf\Client\Locator;

/**
 * Class Wishlist
 * Wish list block in Customer's Activities sidebar in create order on backend
 */
class Wishlist extends Sidebar
{
    /**
     * Wish list locator
     *
     * @var string
     */
    protected $wishlist = '.sidebar-selector';

    /**
     * Wish list items locator
     *
     * @var string
     */
    protected $wishlistItems = '#sidebar_data_wishlist';

    /**
     * Select wish list in Wish list dropdown
     *
     * @param string $name
     * @return bool
     */
    public function selectWishlist($name)
    {
        $this->_rootElement->find($this->wishlist, Locator::SELECTOR_CSS, 'select')->setValue($name);
    }

    /**
     * Get last ordered items block
     *
     * @return \Magento\MultipleWishlist\Test\Block\Adminhtml\Sales\Order\Create\Sidebar\Wishlist\Items
     */
    public function getWishlistItemsBlock()
    {
        return $this->blockFactory->create(
            'Magento\MultipleWishlist\Test\Block\Adminhtml\Sales\Order\Create\Sidebar\Wishlist\Items',
            ['element' => $this->_rootElement->find($this->wishlistItems)]
        );
    }
}
