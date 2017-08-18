<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\TestCase;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Page\Adminhtml\CatalogCategoryIndex;
use Magento\VisualMerchandiser\Test\Page\Adminhtml\CatalogCategoryMerchandiser;
use Magento\Mtf\TestCase\Injectable;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser\Grid;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser\Tile;

class RemoveProductTest extends Injectable
{
    /* tags */
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
     * Inject pages.
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

    /**
     * Delete a product from view.
     *
     * @param Category $category
     * @param string $tab
     * @param bool $saveCategory
     * @return array
     */
    public function test(Category $category, $tab, $saveCategory)
    {
        $category->persist();

        $this->catalogCategoryIndex->open();
        $this->catalogCategoryIndex->getTreeCategories()->selectCategory($category);
        $this->catalogCategoryEdit->getEditForm()->openSection('category_products');
        $merchandiser = $this->catalogCategoryEdit->getMerchandiserApp();
        /* @var Grid|Tile $gridViewTab */
        $gridViewTab = $merchandiser->openTab($tab)->getTab($tab);
        $productGrid = $gridViewTab->getProductGrid();

        $products = $category->getDataFieldConfig('category_products')['source']->getProducts();
        /* @var FixtureInterface $productFixture */
        foreach ($products as $productFixture) {
            $product = $productGrid->getProduct($productFixture);
            $productGrid->deleteProduct($product);
        }

        if ($saveCategory) {
            $this->catalogCategoryEdit->getFormPageActions()->save();
            $this->catalogCategoryEdit->getEditForm()->openSection('category_products');
        }

        return [
            'merchandiser' => $merchandiser,
            'tab' => $tab
        ];
    }
}
