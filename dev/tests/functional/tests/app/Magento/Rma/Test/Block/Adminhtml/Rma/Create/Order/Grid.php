<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\Create\Order;

/**
 * Choose order grid for create rma.
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'id' => [
            'selector' => '#magento_rma_rma_create_order_grid_filter_real_order_id',
        ],
    ];

    /**
     * Locator for link in action column.
     *
     * @var string
     */
    protected $editLink = 'td[class*=real_order_id]';
}
