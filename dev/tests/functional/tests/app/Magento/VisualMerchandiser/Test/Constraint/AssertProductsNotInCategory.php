<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser\Grid;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser\Tile;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\ProductGrid;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Widget\Grid\TileGrid;

class AssertProductsNotInCategory extends AbstractConstraint
{
    /**
     * @var array
     */
    protected $products;

    /**
     * Assert that the product is not in the category.
     *
     * @param Category $category
     * @param Merchandiser $merchandiser
     * @return void
     */
    public function processAssert(
        Category $category,
        Merchandiser $merchandiser
    ) {
        $this->products = $category->getDataFieldConfig('category_products')['source']->getProducts();

        $merchandiser->openTab('mode_grid');
        $this->processViewAssert($merchandiser->getTab('mode_grid'));

        $merchandiser->openTab('mode_tile');
        $this->processViewAssert($merchandiser->getTab('mode_tile'));
    }

    /**
     * Assert for the given view.
     *
     * @param Grid|Tile $view
     * @return void
     */
    protected function processViewAssert($view)
    {
        /* @var ProductGrid|TileGrid $grid */
        $grid = $view->getProductGrid();

        /* @var FixtureInterface $productFixture */
        foreach ($this->products as $productFixture) {
            \PHPUnit_Framework_Assert::assertEquals(
                $grid->isProductVisible($productFixture),
                false,
                "Product was not deleted from view ".get_class($view)
            );
        }
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'VisualMerchandiser test product not in category';
    }
}
