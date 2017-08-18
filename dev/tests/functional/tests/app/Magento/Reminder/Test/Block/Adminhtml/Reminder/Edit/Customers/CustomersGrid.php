<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Block\Adminhtml\Reminder\Edit\Customers;

/**
 * Customer grid on "Matched Customers" tab.
 */
class CustomersGrid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Secondary part of row locator template for getRow() method.
     *
     * @var string
     */
    protected $rowTemplate = 'td[contains(.,normalize-space("%s"))]';

    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'email' => [
            'selector' => 'input[name="grid_email"]',
        ],
        'coupon' => [
            'selector' => 'input[name="grid_code"]',
        ],
    ];
}
