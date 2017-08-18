<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer;

use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Wishlist main block.
 */
class Wishlist extends Block
{
    /**
     * Gift registry drop down selector.
     *
     * @var string
     */
    protected $addGiftRegistry = '.giftregisty-dropdown.add span.action';

    /**
     * Gift registry selector.
     *
     * @var string
     */
    protected $giftRegistry = '//ul[contains(@class, "item")]/li[contains(text(), "%s")]';

    /**
     * Locator value for Product Price.
     *
     * @var string
     */
    protected $price = '.price';

    /**
     * Click to save button.
     *
     * @param string $giftRegistry
     * @return void
     */
    public function addToGiftRegistry($giftRegistry)
    {
        $this->hoverProductBlock();
        $this->openGiftRegistry();
        $this->_rootElement->find(sprintf($this->giftRegistry, $giftRegistry), Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Check that gift registry available in wishlist.
     *
     * @param GiftRegistry $giftRegistry
     * @return bool
     */
    public function isGiftRegistryAvailable(GiftRegistry $giftRegistry)
    {
        $this->hoverProductBlock();
        $addGiftRegistry = $this->_rootElement->find($this->addGiftRegistry);
        if (!$addGiftRegistry->isVisible()) {
            return false;
        }
        $addGiftRegistry->click();
        $selector = sprintf($this->giftRegistry, $giftRegistry->getTitle());
        return $this->_rootElement->find($selector, Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Open gift registry drop down.
     *
     * @return void
     */
    protected function openGiftRegistry()
    {
        $this->_rootElement->find($this->addGiftRegistry)->click();
    }

    /**
     * Hover Product block so that possible actions appear.
     *
     * @return void
     */
    protected function hoverProductBlock()
    {
        $this->_rootElement->find($this->price)->click();
    }
}
