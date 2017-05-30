<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser;

use Magento\Catalog\Test\Block\Adminhtml\Category\Edit\Tab\Product;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\ProductGrid;

/**
 * VisualMerchandiser grid view
 */
class Grid extends Product
{
    /**
     * Returns role grid.
     *
     * @return ProductGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            'Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\ProductGrid',
            ['element' => $this->_rootElement->find($this->productGrid)]
        );
    }
}
