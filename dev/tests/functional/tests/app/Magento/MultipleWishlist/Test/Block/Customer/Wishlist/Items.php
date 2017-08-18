<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Customer\Wishlist;

use Magento\MultipleWishlist\Test\Block\Customer\Wishlist\Items\Product;
use Magento\Mtf\Client\Element;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Class Items
 * Customer multiple wishlist items block on frontend
 */
class Items extends \Magento\Wishlist\Test\Block\Customer\Wishlist\Items
{
    /**
     * Get item product block
     *
     * @param FixtureInterface $product
     * @return Product
     */
    public function getItemProduct(FixtureInterface $product)
    {
        $productBlock = sprintf($this->itemBlock, $product->getName());
        return $this->blockFactory->create(
            \Magento\MultipleWishlist\Test\Block\Customer\Wishlist\Items\Product::class,
            ['element' => $this->_rootElement->find($productBlock, Locator::SELECTOR_XPATH)]
        );
    }
}
