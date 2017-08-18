<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Cart;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Block\Block;

/**
 * Gift options for order block on checkout cart frontend page.
 */
class GiftOptions extends Block
{
    /**
     * Allow Gift Options for Cart.
     *
     * @var string
     */
    protected $allowGiftOptions = '.title';

    /**
     * Gift Options block for Cart.
     *
     * @var string
     */
    protected $giftOptions = '.gift-item-block.block._active';

    /**
     * Gift Wrapping Design Options.
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
    private $cartTotalsLoader = '#loading-mask';

    /**
     * Get Gift Wrappings Available on Checkout Cart.
     *
     * @return array
     */
    public function getGiftWrappingsAvailable()
    {
        $this->_rootElement->find($this->allowGiftOptions)->click();
        $giftWrappings = $this->_rootElement->getElements($this->giftWrappingOptions);
        $getGiftWrappingsAvailable = [];
        foreach ($giftWrappings as $giftWrapping) {
            $giftWrapping->click();
            $getGiftWrappingsAvailable[] = $this->_rootElement->find($this->giftWrappingName)->getText();
        }

        return $getGiftWrappingsAvailable;
    }

    /**
     * Select Gift Wrapping for Order.
     *
     * @param GiftWrapping $giftWrappingDesign
     * @return void
     */
    public function selectGiftWrapping(GiftWrapping $giftWrappingDesign)
    {
        $this->waitForElementNotVisible($this->cartTotalsLoader);
        $this->_rootElement->find($this->allowGiftOptions)->click();
        $this->_rootElement->waitUntil(
            function () {
                return $this->_rootElement->find($this->giftOptions)->isVisible() ? true : null;
            }
        );
        /** @var \Magento\Mtf\Client\ElementInterface[] $giftWrappings */
        $giftWrappings = array_reverse($this->_rootElement->getElements($this->giftWrappingOptions));
        foreach ($giftWrappings as $giftWrapping) {
            $giftWrapping->click();
            if ($this->_rootElement->find($this->giftWrappingName)->getText() == $giftWrappingDesign->getDesign()) {
                break;
            }
        }
        $this->_rootElement->find($this->updateButton)->click();
    }
}
