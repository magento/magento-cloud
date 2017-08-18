<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Block\Adminhtml\Reminder\Edit;

use Magento\Reminder\Test\Block\Adminhtml\Reminder\Edit\Customers\CustomersGrid;

/**
 * "Matched Customers" tab.
 */
class Customers extends \Magento\Backend\Test\Block\Widget\Tab
{
    /**
     * Locator for customers grid.
     *
     * @var string
     */
    protected $customersGrid = '#Matched_Customers';

    /**
     * Get customers grid.
     *
     * @return CustomersGrid
     */
    public function getCusromersGrid()
    {
        return $this->blockFactory->create(
            \Magento\Reminder\Test\Block\Adminhtml\Reminder\Edit\Customers\CustomersGrid::class,
            ['element' => $this->_rootElement->find($this->customersGrid)]
        );
    }
}
