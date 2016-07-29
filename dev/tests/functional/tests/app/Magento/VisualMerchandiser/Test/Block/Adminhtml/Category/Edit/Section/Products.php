<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section;

class Products extends \Magento\Catalog\Test\Block\Adminhtml\Category\Edit\Section\Products
{
    /**
     * Returns role grid.
     *
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\ProductGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            'Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\ProductGrid',
            ['element' => $this->_rootElement->find($this->productGrid)]
        );
    }
}
