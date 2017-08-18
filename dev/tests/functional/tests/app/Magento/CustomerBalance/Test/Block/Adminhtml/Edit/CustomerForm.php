<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Adminhtml\Edit;

use Magento\Customer\Test\Block\Adminhtml\Edit\CustomerForm as ParentForm;

/**
 * Class CustomerForm
 * Form for creation store credits
 */
class CustomerForm extends ParentForm
{
    /**
     * Getting store credit tab object
     *
     * @return \Magento\CustomerBalance\Test\Block\Adminhtml\Customer\Edit\Tab\Tab
     */
    public function getStoreCreditTab()
    {
        return $this->getTab('store_credit');
    }
}
