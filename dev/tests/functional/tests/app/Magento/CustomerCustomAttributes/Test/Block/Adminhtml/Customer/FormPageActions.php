<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Adminhtml\Customer;

use Magento\Backend\Test\Block\FormPageActions as ParentFormPageActions;

/**
 * Form page actions block.
 */
class FormPageActions extends ParentFormPageActions
{
    /**
     * CSS Selector for access button.
     *
     * @var string
     */
    private $acceptButton = '.action-accept';

    /**
     * Click on "Delete" button with acceptAlert.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->browser->find($this->acceptButton)->click();
    }
}
