<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Order;

use Magento\Bundle\Test\Fixture\BundleProduct;
use Magento\Rma\Test\Block\Adminhtml\Product\Bundle\Items as BundleItems;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Grid for choose order item(bundle product).
 */
class BundleGrid extends Grid
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
     * Popup block for choose items of returned bundle product.
     *
     * @var string
     */
    protected $bundleItemsPopup = '//ancestor::div//div[@id="details_container"]';

    /**
     * Select order item.
     *
     * @param FixtureInterface $product
     * @return void
     */
    public function selectItem(FixtureInterface $product)
    {
        /** @var BundleProduct $product */
        $checkoutData = $product->getCheckoutData();
        $bundleOptions = isset($checkoutData['options']['bundle_options'])
            ? $checkoutData['options']['bundle_options']
            : [];
        $labels = [];

        foreach ($bundleOptions as $option) {
            $labels[] = $option['value']['name'];
        }

        $this->searchAndSelect(['sku' => $product->getSku()]);
        $this->getSelectItemsBlock()->fill($labels);
    }

    /**
     * Return popup select bundle items block.
     *
     * @return BundleItems
     */
    protected function getSelectItemsBlock()
    {
        return $this->blockFactory->create(
            \Magento\Rma\Test\Block\Adminhtml\Product\Bundle\Items::class,
            ['element' => $this->_rootElement->find($this->bundleItemsPopup, Locator::SELECTOR_XPATH)]
        );
    }
}
