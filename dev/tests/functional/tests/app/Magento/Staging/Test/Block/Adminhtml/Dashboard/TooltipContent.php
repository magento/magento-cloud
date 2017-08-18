<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml\Dashboard;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Update campaign tooltip content.
 */
class TooltipContent extends Block
{
    /**
     * Edit event link Xpath selector.
     *
     * @var string
     */
    private $editEvent = '//a[contains(@href, "update/edit")]';

    /**
     * Preview event link Xpath selector.
     *
     * @var string
     */
    private $previewEvent = '//a[contains(@href, "update/preview")]';

    /**
     * Get tooltip contents.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->_rootElement->getText();
    }

    /**
     * Click edit event link.
     *
     * @return void
     */
    public function editEvent()
    {
        $this->_rootElement->find($this->editEvent, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Click preview event link.
     *
     * @return void
     */
    public function previewEvent()
    {
        $this->_rootElement->find($this->previewEvent, Locator::SELECTOR_XPATH)->click();
    }
}
