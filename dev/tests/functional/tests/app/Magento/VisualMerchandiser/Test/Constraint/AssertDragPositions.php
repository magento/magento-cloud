<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VisualMerchandiser\Test\Constraint;

use Magento\Catalog\Test\Fixture\Category;
use Magento\VisualMerchandiser\Test\Page\Adminhtml\CatalogCategoryMerchandiser;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\VisualMerchandiser\Test\Block\Adminhtml\Category\Tab\Merchandiser\Grid;

/**
 * Change positions numbers, make sure they work
 */
class AssertDragPositions extends AbstractConstraint
{
    /**
     * @var CatalogCategoryMerchandiser
     */
    protected $merchandiser;

    /**
     * Assert
     *
     * @param CatalogCategoryMerchandiser $merchandiser
     * @return void
     */
    public function processAssert(CatalogCategoryMerchandiser $merchandiser)
    {
        $merchandiser->getEditForm()->openSection('category_products');
        $merchandiser->getMerchandiserApp()->openTab('mode_grid');

        /** @var Grid $tab */
        $tab = $merchandiser->getMerchandiserApp()->getTab('mode_grid');
        $grid = $tab->getProductGrid();

        $skuExpected = $grid->getSkuByIndex(1);
        $grid->dragAndDrop(1, 3);
        $skuActual = $grid->getSkuByIndex(3);

        \PHPUnit_Framework_Assert::assertEquals(
            $skuExpected,
            $skuActual,
            "Re-positioned SKU was wrong. Expected: $skuExpected; Actual: $skuActual"
        );

        $merchandiser->getFormPageActions()->save();

        $merchandiser->getEditForm()->openSection('category_products');
        $merchandiser->getMerchandiserApp()->openTab('mode_grid');
        /** @var Grid $tab */
        $tab = $merchandiser->getMerchandiserApp()->getTab('mode_grid');
        $skuActual = $tab->getProductGrid()->getSkuByIndex(3);

        \PHPUnit_Framework_Assert::assertEquals(
            $skuExpected,
            $skuActual,
            "Saving the positions did not work. Expected: $skuExpected; Actual: $skuActual"
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'VisualMerchandiser drag-and-drop test';
    }
}
