<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\BannerCustomerSegment\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Widget\Test\Fixture\Widget;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Util\Command\Cli\Cache;

/**
 * Perform assertion that Customer Segment dependent banners displayed correctly
 */
class AssertBannerIsVisibleForCustomerSegment extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * @param Customer $customer
     */
    public function processAssert(
        Customer $customer,
        Widget $widget,
        CmsIndex $cmsIndex,
        Cache $cache,
        BrowserInterface $browser
    ) {
        $customer->persist();
        $cache->flush();

        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();

        $cmsIndex->open();
        $widgetText = $widget->getParameters()['entities'][1]->getStoreContents()['value_0'];
        \PHPUnit_Framework_Assert::assertTrue(
            $browser->waitUntil(
                function () use ($cmsIndex, $widget, $widgetText) {
                    return $cmsIndex->getWidgetView()->isWidgetVisible($widget, $widgetText) ? true : null;
                }
            ),
            'Registered Customers only banner is absent on Home page.'
        );

        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class
        )->run();

        $cmsIndex->open();
        $widgetText = $widget->getParameters()['entities'][0]->getStoreContents()['value_0'];
        \PHPUnit_Framework_Assert::assertTrue(
            $browser->waitUntil(
                function () use ($cmsIndex, $widget, $widgetText) {
                    return $cmsIndex->getWidgetView()->isWidgetVisible($widget, $widgetText) ? true : null;
                }
            ),
            'Visitors only banner is absent on Home page.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Segment dependent banners are visible on Home page';
    }
}
