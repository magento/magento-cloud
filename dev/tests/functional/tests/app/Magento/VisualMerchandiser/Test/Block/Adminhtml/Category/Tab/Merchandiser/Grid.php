<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser;

use Magento\Catalog\Test\Block\Adminhtml\Category\Edit\Section\Products;

/**
 * VisualMerchandiser grid view
 */
class Grid extends Products
{
    /**
     * Returns role grid.
     *
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\ProductGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            \Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\ProductGrid::class,
            ['element' => $this->_rootElement->find($this->productGrid)]
        );
    }
}
