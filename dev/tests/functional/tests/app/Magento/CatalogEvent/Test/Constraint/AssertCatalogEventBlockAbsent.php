<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\CatalogEvent\Test\Fixture\CatalogEventEntity;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check invisible Event block on category/product pages.
 */
class AssertCatalogEventBlockAbsent extends AbstractConstraint
{
    /**
     * Category Page on Storefront.
     *
     * @var CatalogCategoryView
     */
    protected $catalogCategoryView;

    /**
     * Index Page on Storefront.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Product Page on Storefront.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Category name.
     *
     * @var string
     */
    protected $categoryName;

    /**
     * Product fixture.
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Assert that Event block is invisible on category/product pages.
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogEventEntity $catalogEvent
     * @param CatalogProductView $catalogProductView
     *
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CatalogEventEntity $catalogEvent,
        CatalogProductView $catalogProductView
    ) {
        $this->catalogCategoryView = $catalogCategoryView;
        $this->cmsIndex = $cmsIndex;
        $this->catalogProductView = $catalogProductView;

        $this->categoryName = $catalogEvent->getCategoryId();
        $this->product = current(
            $catalogEvent->getDataFieldConfig('category_id')['source']
                ->getCategory()
                ->getDataFieldConfig('category_products')['source']
                ->getProducts()
        );

        $this->checkEventBlockOnCategoryPageAbsent();
        $this->checkEventBlockOnProductPageAbsent();
    }

    /**
     * Event block is invisible on Category page.
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
     * Event block is invisible on Product page.
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
     * Text invisible Event Block on category/product pages.
     *
     * @return string
     */
    public function toString()
    {
        return 'Event block is invisible on category/product pages';
    }
}
