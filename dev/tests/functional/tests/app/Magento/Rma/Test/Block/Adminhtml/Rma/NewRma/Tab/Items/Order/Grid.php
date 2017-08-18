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
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Select order item.
     *
     * @param FixtureInterface $product
     * @return void
     */
    public function selectItem(FixtureInterface $product)
    {
        /** @var CatalogProductSimple $product */
        $productConfig = $product->getDataConfig();
        $productType = isset($productConfig['type_id']) ? ucfirst($productConfig['type_id']) : '';
        $productGridClass = 'Magento\Rma\Test\Block\Adminhtml\Rma\NewRma\Tab\Items\Order\\' . $productType . 'Grid';

        $productGrid = $this->blockFactory->create($productGridClass, ['element' => $this->_rootElement]);
        $productGrid->selectItem($product);
    }
}
