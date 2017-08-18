<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Fixture\Banner;
use Magento\Widget\Test\Page\Adminhtml\WidgetInstanceEdit;
use Magento\Widget\Test\Page\Adminhtml\WidgetInstanceNew;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Check that deleted banner is absent in Widget options bunnerGrid and can't be found by name.
 */
class AssertBannerNotInWidgetOptions extends AbstractConstraint
{
    /**
     * Assert that deleted banner is absent in Widget options bunnerGrid and can't be found by name.
     *
     * @param Banner $banner
     * @param FixtureFactory $fixtureFactory
     * @param WidgetInstanceNew $widgetInstanceNew
     * @param WidgetInstanceEdit $widgetInstanceEdit
     * @return void
     */
    public function processAssert(
        Banner $banner,
        FixtureFactory $fixtureFactory,
        WidgetInstanceNew $widgetInstanceNew,
        WidgetInstanceEdit $widgetInstanceEdit
    ) {
        $widget = $fixtureFactory->create(
            \Magento\Banner\Test\Fixture\BannerWidget::class,
            ['dataset' => 'widget_banner_rotator']
        );
        $widgetInstanceNew->open();
        $widgetInstanceNew->getWidgetForm()->fill($widget);
        $widgetInstanceEdit->getWidgetForm()->openTab('widget_options');

        \PHPUnit_Framework_Assert::assertFalse(
            $widgetInstanceEdit->getBannerGrid()->isRowVisible(['banner' => $banner->getName()]),
            'Banner is present on Widget Options tab in Banner grid.'
        );
    }

    /**
     * Banner is absent in Banners grid.
     *
     * @return string
     */
    public function toString()
    {
        return 'Banner is absent on Widget Options tab in Banner grid.';
    }
}
