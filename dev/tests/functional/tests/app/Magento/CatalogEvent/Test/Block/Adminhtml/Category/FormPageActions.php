<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Block\Adminhtml\Category;

use Magento\Backend\Test\Block\FormPageActions as AbstractFormPageActions;

/**
 * Class FormPageActions
 * Page Actions for Catalog Event
 */
class FormPageActions extends AbstractFormPageActions
{
    /**
     * Add Event button.
     *
     * @var string
     */
    protected $addEvent = '#add_event';

    /**
     * Edit Event button.
     *
     * @var string
     */
    protected $editEvent = '#edit_event';

    /**
     * Click on 'Add Event' button.
     *
     * @return void
     */
    public function addCatalogEvent()
    {
        $this->_rootElement->find($this->addEvent)->click();
    }

    /**
     * Click 'Edit Event' button.
     *
     * @return void
     */
    public function editCatalogEvent()
    {
        $this->_rootElement->find($this->editEvent)->click();
    }
}
