<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Order;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Grid for choose order item.
 */
class SimpleGrid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'sku' => [
            'selector' => '[name="sku"]',
        ],
    ];

    /**
     * Select order item.
     *
     * @param FixtureInterface $product
     * @return void
     */
    public function selectItem(FixtureInterface $product)
    {
        $this->searchAndSelect(['sku' => $product->getSku()]);
    }
}
