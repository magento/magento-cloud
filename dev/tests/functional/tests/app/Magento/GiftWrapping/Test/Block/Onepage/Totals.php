<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Onepage;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Cart totals block.
 */
class Totals extends Block
{
    /**
     * Gift Wrapping amount selector.
     *
     * @var string
     */
    private $giftWrappingAmount = '//tr[@class="totals giftwrapping"]/td[@class="amount"]';

    /**
     * Get Cart Totals Gift Wrapping amount.
     *
     * @return string|null
     */
    public function getGiftWrappingAmount()
    {
        $orderGiftWrapping = $this->_rootElement->find(
            $this->giftWrappingAmount,
            Locator::SELECTOR_XPATH
        );
        return $orderGiftWrapping->isVisible() ? $this->escapeCurrency($orderGiftWrapping->getText()) : null;
    }

    /**
     * Method that escapes currency symbols.
     *
     * @param string $price
     * @return string|null
     */
    protected function escapeCurrency($price)
    {
        preg_match("/^\\D*\\s*([\\d,\\.]+)\\s*\\D*$/", $price, $matches);
        return (isset($matches[1])) ? $matches[1] : null;
    }
}
