<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Adminhtml\Sales\Order\Create\Sidebar\Wishlist;

use Magento\GroupedProduct\Test\Fixture\GroupedProduct;
use Magento\Sales\Test\Block\Adminhtml\Order\Create\CustomerActivities\Sidebar;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Class Items
 * Wish list items block on backend
 */
class Items extends Sidebar
{
    // @codingStandardsIgnoreStart
    /**
     * Locator for 'Add To Order' checkbox
     *
     * @var string
     */
    protected $addToOrder = '//tr[td[contains(.,"%s")]][td[contains(.,"%d")]][td/span[contains(., "%d")]]//input[contains(@name,"[add_wishlist_item]")]';
    // @codingStandardsIgnoreEnd

    /**
     * Locator for 'Add to order' link for Grouped product
     *
     * @var string
     */
    protected $addToOrderGrouped = '//tr[td[contains(.,"%s")]]//a[contains(@class, "icon-configure")]';

    /**
     * Select item to add to order
     *
     * @param InjectableFixture $product
     * @param string $qty
     * @return void
     */
    public function selectItemToAddToOrder(InjectableFixture $product, $qty)
    {
        if ($product instanceof GroupedProduct) {
            $this->_rootElement->find(
                sprintf($this->addToOrderGrouped, $product->getName()),
                Locator::SELECTOR_XPATH
            )->click();
        } else {
            $checkBox = $this->_rootElement->find(
                sprintf($this->addToOrder, $product->getName(), $qty, $product->getCheckoutData()['cartItem']['price']),
                Locator::SELECTOR_XPATH,
                'checkbox'
            );
            $checkBox->click();
            $this->_rootElement->click();
            $checkBox->setValue('Yes');
        }
    }
}
