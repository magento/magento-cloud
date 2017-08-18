<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Adminhtml\Customer\Edit;

use Magento\Backend\Test\Block\Widget\Grid;
use Magento\Mtf\Client\Locator;

/**
 * Class Items
 * Backend items gift registry grid
 */
class Items extends Grid
{
    /**
     * Selector for row item
     *
     * @var string
     */
    protected $rowSelector = './/tr[td[contains(.,"%s")]]';

    /**
     * Selector for qty value input
     *
     * @var string
     */
    protected $qtySelector = '[name$="[qty]"]';

    /**
     * Selector for action value input
     *
     * @var string
     */
    protected $actionSelector = '[name$="[action]"]';

    /**
     * Selector for update items and qty's button
     *
     * @var string
     */
    protected $submit = '[data-ui-id="giftregistry-customer-edit-form-update-button"]';

    /**
     * Search and update giftregistry items
     *
     * @param array $productsProperties
     * @return void
     */
    public function searchAndUpdate(array $productsProperties)
    {
        foreach ($productsProperties as $productProperties) {
            $row = $this->_rootElement->find(
                sprintf($this->rowSelector, $productProperties['name']),
                Locator::SELECTOR_XPATH
            );
            $row->find($this->qtySelector)->setValue($productProperties['qty']);
            $row->find($this->actionSelector, Locator::SELECTOR_CSS, 'select')->setValue($productProperties['action']);
        }
        $this->_rootElement->find($this->submit)->click();
    }
}
