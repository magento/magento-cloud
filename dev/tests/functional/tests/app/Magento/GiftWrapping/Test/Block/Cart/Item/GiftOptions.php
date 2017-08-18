<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Cart\Item;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Block\Block;

/**
 * Gift options for items block checkout cart frontend page.
 */
class GiftOptions extends Block
{
    /**
     * Allow Gift Options for individual items.
     *
     * @var string
     */
    protected $allowGiftOptionsForItems = '.action-gift';

    /**
     * Gift Wrapping Design Options
     *
     * @var string
     */
    protected $giftWrappingOptions = '.gift-wrapping-item > span';

    /**
     * Gift Options Update button.
     *
     * @var string
     */
    private $updateButton = ".action-update";

    /**
     * Gift Wrapping Name.
     *
     * @var string
     */
    protected $giftWrappingName = '.gift-wrapping-name';

    /**
     * Cart Totals block's loader.
     *
     * @var string
     */
    private $cartTotalsLoader = '#cart-totals .loading-mask';

    /**
     * Get Gift Wrappings Available on Checkout Cart.
     *
     * @return array
     */
    public function getGiftWrappingsAvailable()
    {
        $this->_rootElement->find($this->allowGiftOptionsForItems)->click();
        $giftWrappings = $this->_rootElement->getElements($this->giftWrappingOptions);
        $getGiftWrappingsAvailable = [];
        foreach ($giftWrappings as $giftWrapping) {
            $giftWrapping->click();
            $getGiftWrappingsAvailable[] = $this->_rootElement->find($this->giftWrappingName)->getText();
        }

        return $getGiftWrappingsAvailable;
    }

    /**
     * Select Gift Wrapping for Item.
     *
     * @param GiftWrapping $giftWrappingDesign
     * @return void
     */
    public function selectGiftWrapping(GiftWrapping $giftWrappingDesign)
    {
        $this->waitForElementNotVisible($this->cartTotalsLoader);
        $this->_rootElement->find($this->allowGiftOptionsForItems)->click();
        /** @var \Magento\Mtf\Client\ElementInterface[] $giftWrappings */
        $giftWrappings = array_reverse($this->_rootElement->getElements($this->giftWrappingOptions));
        foreach ($giftWrappings as $giftWrapping) {
            $giftWrapping->click();
            if ($this->_rootElement->find($this->giftWrappingName)->getText() == $giftWrappingDesign->getDesign()) {
                break;
            }
        }
        $this->_rootElement->find($this->updateButton)->click();
        $this->waitForElementNotVisible($this->cartTotalsLoader);
    }
}
