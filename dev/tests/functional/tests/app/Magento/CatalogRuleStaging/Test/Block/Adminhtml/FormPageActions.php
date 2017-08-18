<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogRuleStaging\Test\Block\Adminhtml;

use Magento\Backend\Test\Block\FormPageActions as PageActions;

/**
 * Class FormPageActions
 * Form page actions block
 */
class FormPageActions extends PageActions
{
    /**
     * Click on "Save and Apply" button
     *
     * @return void
     */
    public function saveAndApply()
    {
        $this->_rootElement->find($this->saveButton)->click();
    }
}
