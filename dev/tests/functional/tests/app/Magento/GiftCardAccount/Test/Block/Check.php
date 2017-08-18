<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Block;

use Magento\Mtf\Block\Block;

/**
 * Check block on customer account page.
 */
class Check extends Block
{
    /**
     * Filter for get data.
     *
     * @var array
     */
    protected $filter = [
        'code' => 'Gift Card: (.*)',
        'balance' => '\nCurrent Balance: \$(.*)',
        'date_expires' => '\nExpires: (\d+\/\d+\/\d+)',
    ];

    /**
     * Info block css selector.
     *
     * @var string
     */
    protected $infoBlock = '.giftcard.info';

    /**
     * Get gift card account data.
     *
     * @param array $filter
     * @return array
     */
    public function getGiftCardAccountData(array $filter)
    {
        $pattern = '';
        $count = 0;
        $result = [];
        foreach ($filter as $key => $value) {
            if (isset($this->filter[$key])) {
                $pattern .= $this->filter[$key];
                $count++;
            }
        }
        $browser = $this->browser;
        $selector = $this->infoBlock;
        $browser->waitUntil(
            function () use ($browser, $selector) {
                $element = $browser->find($selector);
                return $element->isVisible() ? true : null;
            }
        );
        preg_match('/' . $pattern . '/', $this->_rootElement->getText(), $matches);
        if ($count == count($matches) - 1) {
            $index = 1;
            foreach ($filter as $key => $value) {
                $result[$key] = $matches[$index++];
            }
        }
        return $result;
    }
}
