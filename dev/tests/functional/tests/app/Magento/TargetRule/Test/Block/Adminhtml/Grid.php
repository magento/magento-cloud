<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Block\Adminhtml;

use Magento\Backend\Test\Block\Widget\Grid as AbstractGrid;

/**
 * Class Grid
 * Backend target rule grid
 */
class Grid extends AbstractGrid
{
    /**
     * Locator value for name column
     *
     * @var string
     */
    protected $editLink = 'td[class*=col-name]';

    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'id' => [
            'selector' => '[name="rule_id"]',
        ],
        'name' => [
            'selector' => '[name="name"]',
        ],
        'start_on_from' => [
            'selector' => '[name="from_date[from]"]',
        ],
        'applies_to' => [
            'selector' => '[name="apply_to"]',
            'input' => 'select',
        ],
        'status' => [
            'selector' => '[name="is_active"]',
            'input' => 'select',
        ],
    ];
}
