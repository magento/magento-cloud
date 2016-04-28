<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment\Grid;

use Magento\Backend\Test\Block\Widget\Grid;

/**
 * Class Chooser
 * Backend customer segment grid
 */
class Chooser extends Grid
{
    /**
     * An element locator which allows to select entities in grid
     *
     * @var string
     */
    protected $selectItem = 'tbody tr .checkbox';

    /**
     * Chooser grid mapping
     *
     * @var array
     */
    protected $filters = [
        'name' => [
            'selector' => 'input[name="grid_segment_name"]',
        ],
    ];
}
