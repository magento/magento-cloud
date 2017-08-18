<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Customer\Wishlist\Items;

use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;

/**
 * Wish list item product form.
 */
class Product extends \Magento\Wishlist\Test\Block\Customer\Wishlist\Items\Product
{
    /**
     * Product action to wish list drop-down.
     *
     * @var string
     */
    protected $typeActionWishlist = '.%s [data-toggle="dropdown"]';

    /**
     * Product move to wish list drop-down items.
     *
     * @var string
     */
    protected $wishlistItem = 'div.%s span[title="%s"]';

    /**
     * Action product to wish list.
     *
     * @param MultipleWishlist $wishlist
     * @param string $action
     * @return void
     */
    public function actionToWishlist(MultipleWishlist $wishlist, $action)
    {
        $this->hoverProductBlock();
        $this->_rootElement->find(sprintf($this->typeActionWishlist, $action))->click();
        $this->_rootElement->find(sprintf($this->wishlistItem, $action, $wishlist->getName()))->click();
    }

    /**
     * Get data of the root form.
     *
     * @param FixtureInterface|null $fixture
     * @param SimpleElement|null $element
     * @return array
     */
    public function getData(FixtureInterface $fixture = null, SimpleElement $element = null)
    {
        $this->hoverProductBlock();
        return parent::getData($fixture, $element);
    }
}
