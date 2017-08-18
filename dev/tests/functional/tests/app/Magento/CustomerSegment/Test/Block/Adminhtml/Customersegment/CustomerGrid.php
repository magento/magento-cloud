<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment;

use Magento\Backend\Test\Block\Widget\Grid as AbstractGrid;

/**
 * Class CustomerGrid
 * Backend customer grid on Customer Segment page
 */
class CustomerGrid extends AbstractGrid
{
    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'name' => [
            'selector' => 'input[name="grid_name"]',
        ],
        'email' => [
            'selector' => 'input[name="grid_email"]',
        ],
        'group_id' => [
            'selector' => 'select[name="grid_group"]',
            'input' => 'select',
        ],
        'telephone' => [
            'selector' => 'input[name="grid_telephone"]',
        ],
        'postcode' => [
            'selector' => 'input[name="grid_billing_postcode"]',
        ],
        'country_id' => [
            'selector' => 'select[name="grid_billing_country_id"]',
            'input' => 'select',
        ],
        'region_id' => [
            'selector' => 'input[name="grid_billing_region"]',
        ],
    ];
}
