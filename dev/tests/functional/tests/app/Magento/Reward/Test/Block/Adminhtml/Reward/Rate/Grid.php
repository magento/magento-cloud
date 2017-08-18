<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block\Adminhtml\Reward\Rate;

use Magento\Backend\Test\Block\Widget\Grid as AbstractGrid;

/**
 * Class Grid
 * Adminhtml Reward Exchange Rate management grid
 */
class Grid extends AbstractGrid
{
    /**
     * Edit link selector
     *
     * @var string
     */
    protected $editLink = 'td[data-column="rate_id"]';

    /**
     * Initialize block elements
     *
     * @var array
     */
    protected $filters = [
        'rate_id' => [
            'selector' => 'input[name="rate_id"]',
        ],
        'website_id' => [
            'selector' => 'select[name="website_id"]',
            'input' => 'select',
        ],
        'customer_group_id' => [
            'selector' => 'select[name="customer_group_id"]',
            'input' => 'select',
        ],
    ];

    /**
     * Locator value for first row.
     *
     * @var string
     */
    protected $firstRowSelector = '//tbody/tr[./td[contains(@class, "col-rate_id")]][1]';
}
