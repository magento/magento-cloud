<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Report\Customer\Segment;

use Magento\Backend\Test\Block\Widget\Grid as WidgetGrid;

/**
 * Class ReportGrid
 * Customer segment report grid
 */
class ReportGrid extends WidgetGrid
{
    /**
     * Selector for status select
     *
     * @var string
     */
    protected $option = 'select[name="view_mode"]';

    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'segment_id' => [
            'selector' => 'input[name="segment_id"]',
        ],
        'name' => [
            'selector' => 'input[name="name"]',
        ],
        'is_active' => [
            'selector' => 'select[name="is_active"]',
            'input' => 'select',
        ],
        'website' => [
            'selector' => 'select[name="website"]',
            'input' => 'select',
        ],
        'customer_count' => [
            'selector' => 'input[name="customer_count"]',
        ],
    ];
}
