<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Adminhtml\Customer\Edit;

use Magento\Backend\Test\Block\Widget\Grid;
use Magento\Mtf\Client\Locator;

/**
 * Class CartItems
 * Backend cart items gift registry grid
 */
class CartItems extends Grid
{
    /**
     * Selector for row item
     *
     * @var string
     */
    protected $rowSelector = './/tr[td[contains(.,"%s")]]';

    /**
     * An element locator which allows to select entities in grid
     *
     * @var string
     */
    protected $selectItem = '//*[contains(@class,"col-select")]//input';

    /**
     * Search for item and select it
     *
     * @param array $filter
     * @throws \Exception
     */
    public function searchAndSelect(array $filter)
    {
        $selectItem = $this->_rootElement->find(
            sprintf($this->rowSelector, $filter['productName']) . $this->selectItem,
            Locator::SELECTOR_XPATH
        );
        if ($selectItem->isVisible()) {
            $selectItem->click();
        } else {
            throw new \Exception("Searched item was not found by filter\n" . print_r($filter, true));
        }
    }
}
