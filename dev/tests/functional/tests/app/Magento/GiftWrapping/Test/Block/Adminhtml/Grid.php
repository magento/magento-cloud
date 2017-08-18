<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Block\Adminhtml;

use Magento\Backend\Test\Block\Widget\Grid as ParentGrid;

/**
 * Adminhtml Gift Wrapping management grid.
 */
class Grid extends ParentGrid
{
    /**
     * An element locator which allows to select entities in grid.
     *
     * @var string
     */
    protected $selectItem = 'tbody tr .col-select label input';

    /**
     * Initialize block elements.
     *
     * @var array
     */
    protected $filters = [
        'wrapping_id_from' => [
            'selector' => 'input[name="wrapping_id[from]"]',
        ],
        'wrapping_id_to' => [
            'selector' => 'input[name="wrapping_id[to]"]',
        ],
        'design' => [
            'selector' => '#giftwrappingGrid_filter_design',
        ],
        'status' => [
            'selector' => '#giftwrappingGrid_filter_status',
            'input' => 'select',
        ],
        'website_ids' => [
            'selector' => '#giftwrappingGrid_filter_websites',
            'input' => 'select',
        ],
        'base_price' => [
            'selector' => '#giftwrappingGrid_filter_base_price_from',
        ],
    ];
}
