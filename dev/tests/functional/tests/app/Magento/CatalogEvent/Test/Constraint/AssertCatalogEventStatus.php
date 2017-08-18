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
 * Class AssertCatalogEventStatus
 * Check event status on category/product pages
 */
abstract class AssertCatalogEventStatus extends AbstractConstraint
{
    /**
     * Catalog Event status
     *
     * @var string
     */
    protected $eventStatus = '';

    /**
     * Category Page on Frontend
     *
     * @var CatalogCategoryView
     */
    protected $catalogCategoryView;

    /**
     * Index Page on Frontend
     *
     * @var CmsIndex $cmsIndex
     */
    protected $cmsIndex;

    /**
     * Product simple fixture
     *
     * @var CatalogProductSimple
     */
    protected $product;

    /**
     * Product Page on Frontend
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Assert that Event block has $eventStatus
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CatalogEventEntity $catalogEvent
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CatalogEventEntity $catalogEvent,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView
    ) {
        $this->catalogCategoryView = $catalogCategoryView;
        $this->cmsIndex = $cmsIndex;
        $this->product = $product;
        $this->catalogProductView = $catalogProductView;

        $pageEvent = $catalogEvent->getDisplayState();
        if ($pageEvent['category_page'] == "Yes") {
            $this->checkEventStatusOnCategoryPage();
        }
        if ($pageEvent['product_page'] == "Yes") {
            $this->checkEventStatusOnProductPage();
        }
    }

    /**
     * Event block has $this->eventStatus on Category Page
     *
     * @return void
     */
    protected function checkEventStatusOnCategoryPage()
    {
        $categoryName = $this->product->getCategoryIds()[0];
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($categoryName);
        \PHPUnit_Framework_Assert::assertEquals(
            $this->eventStatus,
            $this->catalogCategoryView->getEventBlock()->getEventStatus(),
            'Wrong event status is displayed.'
            . "\nExpected: " . $this->eventStatus
            . "\nActual: " . $this->catalogCategoryView->getEventBlock()->getEventStatus()
        );
    }

    /**
     * Event block has $this->eventStatus on Product Page
     *
     * @return void
     */
    protected function checkEventStatusOnProductPage()
    {
        $categoryName = $this->product->getCategoryIds()[0];
        $this->cmsIndex->open();
        $this->cmsIndex->getTopmenu()->selectCategoryByName($categoryName);
        $this->catalogCategoryView->getListProductBlock()->getProductItem($this->product)->open();
        \PHPUnit_Framework_Assert::assertEquals(
            $this->eventStatus,
            $this->catalogProductView->getEventBlock()->getEventStatus(),
            'Wrong event status is displayed.'
            . "\nExpected: " . $this->eventStatus
            . "\nActual: " . $this->catalogProductView->getEventBlock()->getEventStatus()
        );
    }

    /**
     * Text '$this->eventStatus' status present on the category/product pages
     *
     * @return string
     */
    public function toString()
    {
        return "$this->eventStatus status is present.";
    }
}
