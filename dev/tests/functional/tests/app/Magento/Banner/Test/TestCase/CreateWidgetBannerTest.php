<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\TestCase;

use Magento\Banner\Test\Fixture\BannerWidget;
use Magento\Widget\Test\TestCase\AbstractCreateWidgetEntityTest;

/**
 * Steps:
 * 1. Login to the backend.
 * 2. Open Content > Widgets.
 * 3. Click Add Widget.
 * 4. Fill settings data for Banner widget type according dataset.
 * 5. Click button Continue.
 * 6. Fill widget data according dataset.
 * 7. Perform all assertions.
 *
 * @group Widget_(PS)
 * @ZephyrId MAGETWO-27916
 */
class CreateWidgetBannerTest extends AbstractCreateWidgetEntityTest
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    /* end tags */

    /**
     * Widget fixture
     *
     * @var BannerWidget
     */
    protected $widget;

    /**
     * Creation for New Instance of WidgetEntity.
     *
     * @param BannerWidget $widget
     * @return void
     */
    public function test(BannerWidget $widget)
    {
        // Steps
        $this->widget = $widget;
        $this->widgetInstanceIndex->open();
        $this->widgetInstanceIndex->getPageActionsBlock()->addNew();
        $this->widgetInstanceNew->getWidgetForm()->fill($widget);
        $this->widgetInstanceEdit->getPageActionsBlock()->save();
    }

    /**
     * Removing widget, catalog rules and sales rules.
     *
     * @return void
     */
    public function tearDown()
    {
        if ($this->widget !== null) {
            $this->objectManager->create('Magento\Widget\Test\TestStep\DeleteAllWidgetsStep')->run();
            if ($this->widget->getParameters()['entities'][0]->hasData('banner_catalog_rules')) {
                $this->objectManager->create('Magento\CatalogRule\Test\TestStep\DeleteAllCatalogRulesStep')->run();
            }
            if ($this->widget->getParameters()['entities'][0]->hasData('banner_sales_rules')) {
                $this->objectManager->create('Magento\SalesRule\Test\TestStep\DeleteAllSalesRuleStep')->run();
            }
        }
    }
}
