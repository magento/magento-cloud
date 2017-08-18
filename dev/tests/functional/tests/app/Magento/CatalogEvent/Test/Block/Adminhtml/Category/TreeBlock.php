<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Block\Adminhtml\Category;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class TreeTreeBlock
 * Categories tree block
 */
class TreeBlock extends Block
{
    /**
     * Category tree locator
     *
     * @var string
     */
    protected $treeElement = '.x-tree-root-node';

    /**
     * Select Category
     *
     * @param string $path
     * @return void
     */
    public function selectCategory($path)
    {
        $this->_rootElement->find($this->treeElement, Locator::SELECTOR_CSS, 'tree')->setValue($path);
    }
}
