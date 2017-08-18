<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Mtf\Util\Command\Cli\Cache;
use Magento\CatalogSearch\Test\Page\AdvancedSearch;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Client\BrowserInterface;

/**
 * Perform assertion that Banner Rotator Widget displays series as expected
 *
 * Expected behavior is one banner is displayed per page load.
 * New series started once all banners from previous one were displayed.
 */
class AssertWidgetBannerRotatorSeries extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Assert that created Banner Rotator widget for series displayed on frontent on Home page
     *
     * @param CmsIndex $cmsIndex
     * @param Widget $widget
     * @param Cache $cache
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        Widget $widget,
        Cache $cache,
        BrowserInterface $browser
    ) {
        // Flush cache
        $cache->flush();

        $widgetText = [];
        foreach ($widget->getParameters()['entities'] as $entity) {
            $widgetText[$entity->getStoreContents()['value_0']] = 1;
        }

        while ($widgetText) {
            $cmsIndex->open();

            \PHPUnit_Framework_Assert::assertTrue(
                $browser->waitUntil(
                    function () use ($cmsIndex, $widget, &$widgetText) {
                        $result = null;
                        foreach (array_keys($widgetText) as $content) {
                            $result = $cmsIndex->getWidgetView()->isWidgetVisible($widget, $content);
                            if ($result) {
                                unset($widgetText[$content]);
                                break;
                            }
                        }
                        return $result ? true : null;
                    }
                ),
                'Widget with type ' . $widget->getCode() . ' is absent on Home page.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Widget is present on Home page";
    }
}
