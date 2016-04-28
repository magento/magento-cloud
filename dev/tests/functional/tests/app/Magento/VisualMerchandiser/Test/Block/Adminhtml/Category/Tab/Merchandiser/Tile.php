<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser;

use Magento\Backend\Test\Block\Widget\Tab;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\TileGrid;

/**
 * VisualMerchandiser tile view
 */
class Tile extends Tab
{
    /**
     * @var string
     */
    protected $tileGrid = '[data-role="catalog_category_merchandiser"]';

    /**
     * Returns role grid.
     *
     * @return TileGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            'Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\TileGrid',
            ['element' => $this->_rootElement->find($this->tileGrid)]
        );
    }
}
