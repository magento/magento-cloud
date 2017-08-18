<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Product\Bundle;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Popup block for choose items of returned bundle product.
 */
class Items extends Block
{
    /**
     * Locator for label.
     *
     * @var string
     */
    protected $label = './/label[contains(.,"%s")]';

    /**
     * Locator for "Ok" button.
     *
     * @var string
     */
    protected $buttonOk = './/button[contains(@id,"ok_button")]';

    /**
     * Locator for "Cancel" button.
     *
     * @var string
     */
    protected $buttonCancel = './/button[contains(@id,"cancel_button")]';

    /**
     * Fill popup block.
     *
     * @param array $labels
     * @return void
     */
    public function fill(array $labels)
    {
        foreach ($labels as $label) {
            $this->_rootElement->find(sprintf($this->label, $label), Locator::SELECTOR_XPATH)->click();
        }
        $this->clickOk();
    }

    /**
     * Click "Ok" button.
     *
     * @return void
     */
    public function clickOk()
    {
        $this->_rootElement->find($this->buttonOk, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Click "Cancel" button.
     *
     * @return void
     */
    public function clickCancel()
    {
        $this->_rootElement->find($this->buttonCancel, Locator::SELECTOR_XPATH)->click();
    }
}
