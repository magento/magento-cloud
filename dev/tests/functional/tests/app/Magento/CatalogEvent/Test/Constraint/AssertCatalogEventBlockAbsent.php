<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCatalogEventBlockAbsent
 * Check invisible Event block on category/product pages
 */
class AssertCatalogEventBlockAbsent extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Category Page on Frontend
     *
     * @var CatalogCategoryView
     */
    protected $catalogCategoryView;

    /**
     * Index Page on Frontend
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Product Page on Frontend
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Category Name
     *
     * @var string
     */
    protected $categoryName;

    /**
     * Product.
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Assert that Event block is invisible on category/product pages
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     *
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView
    ) {
        $this->catalogCategoryView = $catalogCategoryView;
        $this->cmsIndex = $cmsIndex;
        $this->catalogProductView = $catalogProductView;

        $this->categoryName = $product->getCategoryIds()[0];
        $this->product = $product;

        $this->checkEventBlockOnCategoryPageAbsent();
        $this->checkEventBlockOnProductPageAbsent();
    }

    /**
     * Event block is invisible on Category page
     *
     * @return void
     */
    protected function checkEventBlockOnCategoryPageAbsent()
    {
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($this->categoryName);
        \PHPUnit_Framework_Assert::assertFalse(
            $this->catalogCategoryView->getEventBlock()->isVisible(),
            "EventBlock is present on Category page."
        );
    }

    /**
     * Event block is invisible on Product page
     *
     * @return void
     */
    protected function checkEventBlockOnProductPageAbsent()
    {
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($this->categoryName);
        $this->catalogCategoryView->getListProductBlock()->getProductItem($this->product)->open();
        \PHPUnit_Framework_Assert::assertFalse(
            $this->catalogProductView->getEventBlock()->isVisible(),
            "EventBlock is present on Product page."
        );
    }

    /**
     * Text invisible Event Block on category/product pages
     *
     * @return string
     */
    public function toString()
    {
        return 'Event block is invisible on category/product pages';
    }
}
