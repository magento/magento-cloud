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
 * Class AssertCatalogEventBlockVisibility
 * Check visible/invisible Event block on catalog page/product pages
 */
class AssertCatalogEventBlockVisibility extends AbstractConstraint
{
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
     * Product
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Assert that Event block is visible/invisible on page according to fixture(catalog page/product pages)
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogEventEntity $catalogEvent
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @param CatalogEventEntity $catalogEventOriginal
     *
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogEventEntity $catalogEvent,
        CatalogCategoryView $catalogCategoryView,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView,
        CatalogEventEntity $catalogEventOriginal = null
    ) {
        $this->catalogCategoryView = $catalogCategoryView;
        $this->cmsIndex = $cmsIndex;
        $this->catalogProductView = $catalogProductView;

        $this->categoryName = $product->getCategoryIds()[0];
        $this->product = $product;

        $catalogEventData = ($catalogEventOriginal !== null)
            ? array_merge($catalogEventOriginal->getData(), $catalogEvent->getData())
            : $catalogEvent->getData();
        $this->checkEvent($catalogEventData['display_state']);
    }

    /**
     * Check pageEvent
     *
     * @param $pageEvent
     */
    protected function checkEvent($pageEvent)
    {
        if ($pageEvent['category_page'] == "Yes") {
            $this->checkEventBlockOnCategoryPagePresent();
        } else {
            $this->checkEventBlockOnCategoryPageAbsent();
        }
        if ($pageEvent['product_page'] == "Yes") {
            $this->checkEventBlockOnProductPagePresent();
        } else {
            $this->checkEventBlockOnProductPageAbsent();
        }
    }

    /**
     * Event block is visible on Category page
     *
     * @return void
     */
    protected function checkEventBlockOnCategoryPagePresent()
    {
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($this->categoryName);
        \PHPUnit_Framework_Assert::assertTrue(
            $this->catalogCategoryView->getEventBlock()->isVisible(),
            "EventBlock is absent on Category page."
        );
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
     * Event block is visible on Product page
     *
     * @return void
     */
    protected function checkEventBlockOnProductPagePresent()
    {
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($this->categoryName);
        $this->catalogCategoryView->getListProductBlock()->getProductItem($this->product)->open();
        \PHPUnit_Framework_Assert::assertTrue(
            $this->catalogProductView->getEventBlock()->isVisible(),
            "EventBlock is absent on Product page."
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
     * Text visible/invisible Event block on page according to fixture(catalog page/product pages)
     *
     * @return string
     */
    public function toString()
    {
        return 'Event block is visible/invisible on catalog/product pages according to fixture.';
    }
}
