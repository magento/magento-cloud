<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SalesArchive\Test\Block\Adminhtml\Sales\Archive\Order\Shipment;

/**
 * Sales archive shipments grid.
 */
class Grid extends \Magento\Ui\Test\Block\Adminhtml\DataGrid
{
    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'shipment_id' => [
            'selector' => '[name="increment_id"]',
        ],
        'order_id' => [
            'selector' => '[name="order_increment_id"]',
        ],
    ];
}
