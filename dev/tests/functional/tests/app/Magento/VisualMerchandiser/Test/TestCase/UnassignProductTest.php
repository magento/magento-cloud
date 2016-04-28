<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\TestCase;

use Magento\Mtf\TestCase\Injectable;
use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryIndex;
use Magento\VisualMerchandiser\Test\Page\Adminhtml\CatalogCategoryMerchandiser;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\AddProduct\NameTab;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser\Tile as TileTab;

class UnassignProductTest extends Injectable
{
    /* tags */
    const DOMAIN = 'MX';
    const TEST_TYPE = 'acceptance_test';
    /* end tags */

    /**
     * @var CatalogCategoryIndex
     */
    protected $catalogCategoryIndex;

    /**
     * @var CatalogCategoryMerchandiser
     */
    protected $catalogCategoryEdit;

    /**
     * Inject pages
     *
     * @param CatalogCategoryIndex $catalogCategoryIndex
     * @param CatalogCategoryMerchandiser $catalogCategoryEdit
     * @return void
     */
    public function __inject(
        CatalogCategoryIndex $catalogCategoryIndex,
        CatalogCategoryMerchandiser $catalogCategoryEdit
    ) {
        $this->catalogCategoryIndex = $catalogCategoryIndex;
        $this->catalogCategoryEdit = $catalogCategoryEdit;
    }

    public function test(Category $category)
    {
        $category->persist();
        $this->catalogCategoryIndex->open();
        $this->catalogCategoryIndex->getTreeCategories()->selectCategory($category);
        $this->catalogCategoryEdit->getEditForm()->openTab('category_products');
        $merchandiser = $this->catalogCategoryEdit->getMerchandiserApp();

        /* @var TileTab $tab */
        $tab = $merchandiser
            ->openTab('mode_tile')
            ->getTab('mode_tile');

        $dialog = $tab->getProductGrid()->getAddProductDialog();
        $dialog->openDialog();

        /* @var NameTab $addProductNameTab */
        $addProductNameTab = $dialog->openTab('name_tab')->getTab('name_tab');
        $dataGrid = $addProductNameTab->getDataGrid();

        $products = $category->getDataFieldConfig('category_products')['source']->getProducts();

        /* @var FixtureInterface $productFixture */
        foreach ($products as $productFixture) {
            $dataGrid->searchByNameAndSelect(['name' => $productFixture->getData('name')]);
        }

        // Save dialog modifications
        $dialog->saveAndClose();
        $tab->getProductGrid()->waitLoader();

        $this->assertProductDeletedFromAllViews($merchandiser, $products);

        // Save category
        $this->catalogCategoryEdit->getFormPageActions()->save();
        $this->catalogCategoryEdit->getEditForm()->openTab('category_products');

        return ['merchandiser' => $merchandiser];
    }

    protected function assertProductDeletedFromAllViews(
        \Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser $merchandiser,
        array $products
    ) {
        foreach (['mode_grid', 'mode_tile'] as $viewMode) {
            // Removed from UI
            $tab = $merchandiser->openTab($viewMode)->getTab($viewMode);
            $grid = $tab->getProductGrid();

            /* @var FixtureInterface $productFixture */
            foreach ($products as $productFixture) {
                \PHPUnit_Framework_Assert::assertEquals(
                    $grid->isProductVisible($productFixture),
                    false,
                    "Product was not deleted from view " . get_class($tab)
                );
            }
        }
    }
}
