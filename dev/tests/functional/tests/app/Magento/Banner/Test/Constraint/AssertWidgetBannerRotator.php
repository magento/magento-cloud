<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\CatalogSearch\Test\Page\AdvancedSearch;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that created Banner Rotator widget displayed on frontend on Home page and on Advanced Search.
 */
class AssertWidgetBannerRotator extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that created Banner Rotator widget displayed on frontent on Home page and on Advanced Search.
     *
     * @param CmsIndex $cmsIndex
     * @param AdvancedSearch $advancedSearch
     * @param Widget $widget
     * @param AdminCache $adminCache
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        AdvancedSearch $advancedSearch,
        Widget $widget,
        AdminCache $adminCache
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getActionsBlock()->flushMagentoCache();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $cmsIndex->open();
        $widgetText = $widget->getParameters()['entities'][0]->getStoreContents()['value_0'];
        \PHPUnit_Framework_Assert::assertTrue(
            $cmsIndex->getWidgetView()->isWidgetVisible($widget, $widgetText),
            'Widget with type ' . $widget->getCode() . ' is absent on Home page.'
        );
        $cmsIndex->getFooterBlock()->openAdvancedSearch();
        $cmsIndex->getLinksBlock()->waitWelcomeMessage();
        $cmsIndex->getCmsPageBlock()->waitPageInit();
        \PHPUnit_Framework_Assert::assertTrue(
            $advancedSearch->getWidgetView()->isWidgetVisible($widget, $widgetText),
            'Widget with type ' . $widget->getCode() . ' is absent on Advanced Search page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Widget is present on Home page and on Advanced Search.";
    }
}
