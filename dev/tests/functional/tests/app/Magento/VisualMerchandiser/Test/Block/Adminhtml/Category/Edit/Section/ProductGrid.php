<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Edit\Section;

use \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct\NameTab;
use Magento\Mtf\Client\Locator;

class ProductGrid extends \Magento\Catalog\Test\Block\Adminhtml\Category\Edit\Section\ProductGrid
{
    /**
     * 'Add Product' dialog XPath locator
     *
     * @var string
     */
    protected $addProductDialog = '//aside[contains(@class,"show")]';

    /**
     * Search for item and select it
     *
     * @param array $filter
     * @throws \Exception
     */
    public function searchAndSelect(array $filter)
    {
        $dialog = $this->getAddProductDialog();
        $dialog->openDialog();

        /* @var \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct\NameTab $nameTab */
        $nameTab = $dialog
            ->openTab(NameTab::NAME_TAB)
            ->getTab(NameTab::NAME_TAB);

        $grid = $nameTab->getDataGrid();
        $grid->waitLoader();
        $grid->searchByNameAndSelect($filter);

        $dialog->saveAndClose();
        $grid->waitLoader();
    }

    /**
     * @return \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct
     */
    public function getAddProductDialog()
    {
        return $this->blockFactory->create(
            \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct::class,
            ['element' => $this->browser->find($this->addProductDialog, Locator::SELECTOR_XPATH)]
        );
    }

    /**
     * Search item via grid filter
     *
     * @param array $filter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function search(array $filter)
    {
    }
}
