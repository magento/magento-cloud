<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser;

use Magento\Ui\Test\Block\Adminhtml\Section;

/**
 * VisualMerchandiser tile view
 */
class Tile extends Section
{
    /**
     * @var string
     */
    protected $tileGrid = '[data-role="catalog_category_merchandiser"]';

    /**
     * Returns role grid.
     *
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\TileGrid
     */
    public function getProductGrid()
    {
        return $this->blockFactory->create(
            \Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\TileGrid::class,
            ['element' => $this->_rootElement->find($this->tileGrid)]
        );
    }
}
