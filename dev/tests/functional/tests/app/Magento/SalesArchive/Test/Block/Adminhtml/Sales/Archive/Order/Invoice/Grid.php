<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Block\Adminhtml\Sales\Archive\Order\Invoice;

/**
 * Sales archive invoices grid.
 */
class Grid extends \Magento\Ui\Test\Block\Adminhtml\DataGrid
{
    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'invoice_id' => [
            'selector' => 'input[name="increment_id"]',
        ],
        'order_id' => [
            'selector' => 'input[name="order_increment_id"]',
        ],
    ];

    /**
     * Locator value for "View" link inside action column.
     *
     * @var string
     */
    protected $editLink = '.action-menu-item[href*="view"]';
}
