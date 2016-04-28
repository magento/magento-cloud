<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Constraint;

use Magento\Backend\Test\Page\Adminhtml\AdminCache;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that widget hierarchy node link is present on Home page and after click to link widget redirects to page with
 * cms menu.
 */
class AssertWidgetHierarchyNodeLinkOnHomePage extends AbstractConstraint
{
    /**
     * Assert that widget hierarchy node link is present on Home page and after click to link widget redirects to page
     * with cms menu.
     *
     * @param CmsIndex $cmsIndex
     * @param Widget $widget
     * @param AdminCache $adminCache
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        Widget $widget,
        AdminCache $adminCache
    ) {
        // Flush cache
        $adminCache->open();
        $adminCache->getActionsBlock()->flushMagentoCache();
        $adminCache->getMessagesBlock()->waitSuccessMessage();

        $cmsIndex->open();
        $nodeLinkText = $widget->getParameters()['entities'][0]->getIdentifier();
        \PHPUnit_Framework_Assert::assertTrue(
            $cmsIndex->getWidgetView()->isWidgetVisible($widget, $nodeLinkText),
            'Widget hierarchy node link is absent on Home page.'
        );

        $cmsIndex->getWidgetView()->clickToWidget($widget, $nodeLinkText);
        \PHPUnit_Framework_Assert::assertTrue(
            $cmsIndex->getCmsHierarchyNodeBlock()->cmsMenuIsVisible(),
            'Cms menu is absent on frontend page.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Widget hierarchy node link is present on Home page and after click on link widget redirects to '
        . 'page with cms menu.';
    }
}
