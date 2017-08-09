<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\VisualMerchandiser\Test\Page\Adminhtml\CatalogCategoryMerchandiser;

/**
 * Assert products are visible in products grid on category edit page.
 */
class AssertProductsInCategory extends AbstractConstraint
{
    /**
     * Products fixture.
     *
     * @var array
     */
    private $products;

    /**
     * Assert that products are in the category.
     *
     * @param CatalogCategoryMerchandiser $catalogCategoryEdit
     * @param array $products
     * @return void
     */
    public function processAssert(
        CatalogCategoryMerchandiser $catalogCategoryEdit,
        array $products
    ) {
        $this->products = $products;
        $catalogCategoryEdit->getEditForm()->openSection('category_products');
        $merchandiser = $catalogCategoryEdit->getMerchandiserApp();
        $merchandiser->openTab('mode_grid');
        $view = $merchandiser->getTab('mode_grid');
        $grid = $view->getProductGrid();

        /* @var FixtureInterface $productFixture */
        foreach ($this->products as $productFixture) {
            \PHPUnit_Framework_Assert::assertEquals(
                $grid->isProductVisible($productFixture),
                true,
                "Product wasn\'t added to category"
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return 'Products are assigned to category';
    }
}
