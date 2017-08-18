<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block;

use Magento\Mtf\Block\Block;

/**
 * Tooltip block to get different messages about reward points.
 */
class Tooltip extends Block
{
    /**
     * Message CSS selector on page.
     *
     * @var string
     */
    protected $messageSelector = '.reward-register > :first-child';

    /**
     * Message addition of reward points.
     *
     * @return string
     */
    public function getRewardMessage()
    {
        $this->waitForElementVisible($this->messageSelector);
        $element = $this->_rootElement->find($this->messageSelector);
        $message = $element->getText();

        return $message;
    }
}
