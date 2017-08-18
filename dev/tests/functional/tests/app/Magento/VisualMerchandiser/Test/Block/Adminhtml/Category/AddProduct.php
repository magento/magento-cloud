<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category;

use Magento\Backend\Test\Block\Widget\FormTabs;

class AddProduct extends FormTabs
{
    /**
     * CSS locator of the loader.
     *
     * @var string
     */
    protected $loader = '[data-role="spinner"]';

    /**
     * CSS locator of Add Products button.
     *
     * @var string
     */
    protected $addProducts = '#catalog_category_add_product_tabs';

    /**
     * CSS locator of the search text field.
     *
     * @var string
     */
    protected $searchField = '.data-grid-search-control';

    /**
     * Popup header selector.
     *
     * @var string
     */
    protected $popupHeader = 'header';

    /**
     * Open Add Products dialog.
     *
     * @return $this
     */
    public function openDialog()
    {
        $this->browser->find($this->addProducts)->click();
        $this->browser->waitUntil(
            function () {
                $element = $this->browser->find($this->searchField);
                return $element->isVisible() == true ? true : null;
            }
        );
        $this->waitLoader();
        return $this;
    }

    /**
     * Open tab.
     *
     * @param string $tabName
     * @return FormTabs
     */
    public function openTab($tabName)
    {
        $this->_rootElement->find($this->popupHeader)->hover();
        $this->getContainerElement($tabName)->click();
        return $this;
    }

    /**
     * Close the dialog and save data.
     *
     * @return $this
     */
    public function saveAndClose()
    {
        $this->browser->find('.modal-footer button')->click();
        $this->waitLoader();
        return $this;
    }

    /**
     * Wait until the loader disappears.
     *
     * @return $this
     */
    protected function waitLoader()
    {
        $this->browser->waitUntil(
            function () {
                $element = $this->browser->find($this->loader);
                return $element->isVisible() == false ? true : null;
            }
        );
        return $this;
    }
}
