<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Cart;

use Magento\Mtf\Client\Locator;

/**
 * Cart totals block
 */
class Totals extends \Magento\Checkout\Test\Block\Cart\Totals
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
     * @return string
     */
    public function getGiftWrappingAmount()
    {
        $orderGiftWrapping = $this->_rootElement->waitUntil(
            function () {
                $element = $this->_rootElement->find(
                    $this->giftWrappingAmount,
                    Locator::SELECTOR_XPATH
                );
                return $element->isVisible() ? $element : null;
            }
        );

        return $this->escapeCurrency($orderGiftWrapping->getText());
    }
}
