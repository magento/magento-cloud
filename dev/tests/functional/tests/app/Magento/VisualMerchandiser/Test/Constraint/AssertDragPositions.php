<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
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
        $merchandiser->getMerchandiserApp()->openTab('mode_grid');

        /** @var Grid $tab */
        $tab = $merchandiser->getMerchandiserApp()->getTab('mode_grid');
        $grid = $tab->getProductGrid();

        $src = $grid->getItemByIndex(1);
        $skuToTest = $src->find('.col-sku')->getText();

        $src->dragAndDrop($grid->getItemByIndex(3));

        $sku = $grid->getItemByIndex(3)->find('.col-sku')->getText();

        \PHPUnit_Framework_Assert::assertEquals(
            $skuToTest,
            $sku,
            "Re-positioned SKU was wrong"
        );

        $merchandiser->getFormPageActions()->save();

        $merchandiser->getEditForm()->openTab('category_products');
        $merchandiser->getMerchandiserApp()->openTab('mode_grid');

        /** @var Grid $tab */
        $tab = $merchandiser->getMerchandiserApp()->getTab('mode_grid');
        $sku = $tab->getProductGrid()->getItemByIndex(3)->find('.col-sku')->getText();

        \PHPUnit_Framework_Assert::assertEquals(
            $skuToTest,
            $sku,
            "Saving the positions did not work"
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
