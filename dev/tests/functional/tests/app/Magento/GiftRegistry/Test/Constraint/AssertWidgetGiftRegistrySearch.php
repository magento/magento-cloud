<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that created Gift Registry Search widget displayed on frontend in Catalog.
 */
class AssertWidgetGiftRegistrySearch extends AbstractConstraint
{
    /**
     * Text in title of widget block.
     */
    const WIDGET_TEXT = 'Gift Registry Search';

    /**
     * Assert that created Gift Registry Search widget displayed on frontend in Catalog.
     *
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param Widget $widget
     * @param AdminCache $adminCache
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        Widget $widget,
        AdminCache $adminCache
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getActionsBlock()->flushMagentoCache();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $cmsIndex->open();
        $categoryName = $widget->getWidgetInstance()[0]['entities']->getName();
        $cmsIndex->getTopmenu()->selectCategoryByName($categoryName);
        \PHPUnit_Framework_Assert::assertTrue(
            $catalogCategoryView->getWidgetView()->isWidgetVisible($widget, self::WIDGET_TEXT),
            'Gift Registry Search widget is absent on Category page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Gift Registry Search widget is present on Category page.";
    }
}
