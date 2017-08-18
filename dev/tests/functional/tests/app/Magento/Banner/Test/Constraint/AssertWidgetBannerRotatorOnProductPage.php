<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that created Banner Rotator widget displayed on frontend on Product page.
 */
class AssertWidgetBannerRotatorOnProductPage extends AbstractConstraint
{
    /**
     * Assert that created Banner Rotator widget displayed on frontend on Product page.
     *
     * @param CatalogProductView $productView
     * @param BrowserInterface $browser
     * @param Widget $widget
     * @param AdminCache $adminCache
     * @param CmsIndex $cmsIndex
     * @return void
     */
    public function processAssert(
        CatalogProductView $productView,
        BrowserInterface $browser,
        Widget $widget,
        AdminCache $adminCache,
        CmsIndex $cmsIndex
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getActionsBlock()->flushMagentoCache();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $urlKey = $widget->getWidgetInstance()[0]['entities']->getUrlKey();
        $browser->open($_ENV['app_frontend_url'] . $urlKey . '.html');
        $widgetText = $widget->getParameters()['entities'][0]->getStoreContents()['value_0'];
        $cmsIndex->getLinksBlock()->waitWelcomeMessage();
        $cmsIndex->getCmsPageBlock()->waitPageInit();
        \PHPUnit_Framework_Assert::assertTrue(
            $productView->getWidgetView()->isWidgetVisible($widget, $widgetText),
            'Widget is absent on Product page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Widget is present on Product page.";
    }
}
