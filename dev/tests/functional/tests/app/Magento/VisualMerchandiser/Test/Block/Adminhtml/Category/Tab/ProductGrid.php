<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab;

class ProductGrid extends \Magento\Catalog\Test\Block\Adminhtml\Category\Tab\ProductGrid
{
    const NAME_TAB = 'name_tab';
    const SKU_TAB = 'sku_tab';

    /**
     * @var string
     */
    protected $addProductDialog = '[data-role="catalog_category_add_product_content"]';

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
            ->openTab(self::NAME_TAB)
            ->getTab(self::NAME_TAB);

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
            'Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct',
            ['element' => $this->browser->find($this->addProductDialog)]
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
