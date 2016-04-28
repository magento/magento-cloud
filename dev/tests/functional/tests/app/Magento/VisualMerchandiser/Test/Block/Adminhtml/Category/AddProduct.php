<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category;

use Magento\Backend\Test\Block\Widget\FormTabs;

class AddProduct extends FormTabs
{
    /**
     * Magento loader
     *
     * @var string
     */
    protected $loader = '[data-role="spinner"]';

    /**
     * Open the dialog
     */
    public function openDialog()
    {
        $this->browser->find('#catalog_category_add_product_tabs')->click();
    }

    /**
     * Close the dialog and save data
     */
    public function saveAndClose()
    {
        $this->browser->find('.modal-footer button')->click();
        $this->waitLoader();
    }

    /**
     * Wait until loaded
     */
    protected function waitLoader()
    {
        $this->browser->waitUntil(
            function () {
                $element = $this->browser->find($this->loader);
                return $element->isVisible() == false ? true : null;
            }
        );
    }
}
