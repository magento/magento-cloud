<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Adminhtml\Order;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;

/**
 * Class Create
 * Adminhtml Gift Wrapping order create block
 */
class Create extends Block
{
    /**
     * Gift Wrapping design block locator
     *
     * @var string
     */
    protected $giftWrappingDesignBlock = '#giftwrapping_design';

    /**
     * Order Totals Loader.
     *
     * @var string
     */
    private $orderTotalsLoadingMask = '.loading-mask';

    /**
     * Check if Gift Wrapping design is available on order creation page
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
     * Select GiftWrapping design from the drop down.
     * Deselects GiftWrapping design from drop down if $giftWrapping is null.
     *
     * @param GiftWrapping|null $giftWrapping
     * @return void
     */
    public function selectGiftWrappingDesign(GiftWrapping $giftWrapping = null)
    {
        $giftWrappingDesign = $giftWrapping ? $giftWrapping->getDesign() : null;
        $select = $this->_rootElement->find($this->giftWrappingDesignBlock, Locator::SELECTOR_CSS, 'select');
        $select->setValue($giftWrappingDesign);
        $this->waitForElementNotVisible($this->orderTotalsLoadingMask);
    }
}
