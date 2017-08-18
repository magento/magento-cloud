<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Cybersource\Test\Block\Sandbox;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Menu block on Cybersource sandbox.
 */
class Menu extends Block
{
    /**
     * Menu item selector.
     *
     * @var string
     */
    protected $menuItemSelector = ".//ul[@id='menu_beta_ul']/li/a[contains(text(), '%s')]";

    /**
     * Click menu item, e.g. $itemName = 'Decision Manager' or $itemName = 'Tools & Settings'
     *
     * @param string $itemName
     * @return void
     */
    public function selectMenuItem($itemName)
    {
        $this->_rootElement->find(
            sprintf($this->menuItemSelector, $itemName),
            Locator::SELECTOR_XPATH
        )->click();
    }
}
