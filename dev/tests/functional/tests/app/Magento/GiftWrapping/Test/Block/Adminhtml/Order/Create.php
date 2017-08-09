<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Adminhtml\Order;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Adminhtml Gift Wrapping order create block.
 */
class Create extends Block
{
    /**
     * Gift Wrapping design block locator.
     *
     * @var string
     */
    protected $giftWrappingDesignBlock = '#giftwrapping_design';

    /**
     * Page loading mask.
     *
     * @var string
     */
    protected $loadingMask = '.loading-mask';

    /**
     * Check if Gift Wrapping design is available on order creation page.
     *
     * @param string $giftWrappingDesign
     * @return bool
     */
    public function isGiftWrappingAvailable($giftWrappingDesign)
    {
        $giftWrappings = $this->_rootElement->find($this->giftWrappingDesignBlock)->getText();
        return strpos($giftWrappings, $giftWrappingDesign);
    }

    /**
     * Set Gift Wrapping design.
     *
     * @param GiftWrapping|null $giftWrapping
     * @return void
     */
    public function setGiftWrappingDesign(GiftWrapping $giftWrapping = null)
    {
        $giftWrappings = $this->_rootElement->find($this->giftWrappingDesignBlock, Locator::SELECTOR_CSS, 'select');
        $value = is_object($giftWrapping) ? $giftWrapping->getDesign() : $giftWrapping;
        $giftWrappings->setValue($value);
        $this->waitPageLoaded();
    }

    /**
     * Wait until order page loading mask disappear.
     */
    public function waitPageLoaded()
    {
        $this->waitForElementNotVisible($this->loadingMask);
    }
}
