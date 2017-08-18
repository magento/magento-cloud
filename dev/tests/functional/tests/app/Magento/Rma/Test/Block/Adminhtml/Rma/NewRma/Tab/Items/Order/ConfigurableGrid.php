<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Order;

use Magento\ConfigurableProduct\Test\Fixture\ConfigurableProduct;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Grid for choose order item(configurable product).
 */
class ConfigurableGrid extends Grid
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
        $this->searchAndSelect(['sku' => $this->prepareSku($product)]);
    }

    /**
     * Prepare configurable product sku.
     *
     * @param ConfigurableProduct $product
     * @return string
     */
    public function prepareSku(ConfigurableProduct $product)
    {
        $checkoutData = $product->getCheckoutData();
        $checkoutOptions = isset($checkoutData['options']['configurable_options'])
            ? $checkoutData['options']['configurable_options']
            : [];
        $configurableAttributesData = $product->getConfigurableAttributesData();
        $matrixKey = [];

        foreach ($checkoutOptions as $checkoutOption) {
            $matrixKey[] = $checkoutOption['title'] . ':' . $checkoutOption['value'];
        }
        $matrixKey = implode(' ', $matrixKey);

        return $configurableAttributesData['matrix'][$matrixKey]['sku'];
    }
}
