<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Constraint;

use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that created Order By Sku widget displayed on frontend in Catalog.
 */
class AssertWidgetOrderBySkuOnCategoryPage extends AbstractConstraint
{
    /**
     * Assert that created Order By Sku widget displayed on frontend in Catalog.
     *
     * @param Category $category
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param Widget $widget
     * @param AdminCache $adminCache
     * @return void
     */
    public function processAssert(
        Category $category,
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        Widget $widget,
        AdminCache $adminCache
    ) {
        $category->persist();

        // Flush cache
        $adminCache->open();
        $adminCache->getActionsBlock()->flushMagentoCache();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $cmsIndex->open();
        $cmsIndex->getTopmenu()->selectCategoryByName($category->getName());
        \PHPUnit_Framework_Assert::assertTrue(
            $catalogCategoryView->getWidgetView()->isWidgetVisible($widget, "Order by SKU"),
            'Widget is absent on Category page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Widget is present on Category page.";
    }
}
