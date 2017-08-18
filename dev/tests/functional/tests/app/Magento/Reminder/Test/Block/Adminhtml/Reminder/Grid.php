<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Block\Adminhtml\Reminder;

/**
 * Reminder grid.
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'rule_id' => [
            'selector' => 'input[name="rule_id"]',
        ],
        'name' => [
            'selector' => 'input[name="name"]',
        ],
        'from_date_from' => [
            'selector' => 'input[name="from_date[from]"]',
            'input' => 'datepicker',
        ],
        'from_date_to' => [
            'selector' => 'input[name="from_date[to]"]',
            'input' => 'datepicker',
        ],
        'to_date_from' => [
            'selector' => 'input[name="to_date[from]"]',
            'input' => 'datepicker',
        ],
        'to_date_to' => [
            'selector' => 'input[name="to_date[to]"]',
            'input' => 'datepicker',
        ],
        'status' => [
            'selector' => 'select[name="is_active"]',
            'input' => 'select',
        ],
        'website' => [
            'selector' => 'select[name="rule_website"]',
            'input' => 'select',
        ],
    ];

    /**
     * Locator value for link in action column.
     *
     * @var string
     */
    protected $editLink = 'tbody tr .col-name';
}
