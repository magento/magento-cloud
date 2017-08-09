<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section;

class Products extends \Magento\Catalog\Test\Block\Adminhtml\Category\Edit\Section\Products
{
    /**
     * Match products by rule block locator.
     *
     * @var string
     */
    private $smartCategorySwitcher = '.smart-category-switcher';

    /**
     * Returns role grid.
     *
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\ProductGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\ProductGrid::class,
            ['element' => $this->_rootElement->find($this->productGrid)]
        );
    }

    /**
     * Returns category rules block.
     *
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\SmartCategoryBlock
     */
    public function getSmartCategoryBlock()
    {
        return $this->blockFactory->create(
            \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section\SmartCategoryBlock::class,
            ['element' => $this->_rootElement->find($this->smartCategorySwitcher)]
        );
    }
}
