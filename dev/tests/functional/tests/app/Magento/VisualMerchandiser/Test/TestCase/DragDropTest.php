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

class DragDropTest extends Injectable
{
    /* tags */
    /* end tags */

    /**
     * @var CatalogCategoryMerchandiser
     */
    protected $merchandiser;

    /**
     * @var CatalogCategoryIndex
     */
    protected $catalogCategoryIndex;

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
        $this->merchandiser = $catalogCategoryEdit;
    }

    /**
     * Change positions in grid/tile view
     *
     * @param Category $category
     * @return void
     */
    public function test(Category $category)
    {
        $category->persist();

        $this->catalogCategoryIndex->open();
        $this->catalogCategoryIndex->getTreeCategories()->selectCategory($category);

        $this->merchandiser->getEditForm()->openSection('category_products');
    }
}
