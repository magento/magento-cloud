<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Tab;

class Product extends \Magento\Catalog\Test\Block\Adminhtml\Category\Edit\Tab\Product
{
    /**
     * Returns role grid.
     *
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\ProductGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            'Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\ProductGrid',
            ['element' => $this->_rootElement->find($this->productGrid)]
        );
    }
}
