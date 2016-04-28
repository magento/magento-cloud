<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma;

use Magento\Backend\Test\Block\Widget\FormTabs;
use Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab\Items;

/**
 * Rma tabs on view page.
 */
class Edit extends FormTabs
{
    /**
     * Locator for rma items grid.
     *
     * @var string
     */
    protected $rmaItemsGrid = '#rma_info_tabs_items_section_content';

    /**
     * Return rma items grid.
     *
     * @return Items
     */
    public function getItemsGrid()
    {
        return $this->blockFactory->create(
            '\Magento\Rma\Test\Block\Adminhtml\Rma\Edit\Tab\Items',
            ['element' => $this->_rootElement->find($this->rmaItemsGrid)]
        );
    }
}
