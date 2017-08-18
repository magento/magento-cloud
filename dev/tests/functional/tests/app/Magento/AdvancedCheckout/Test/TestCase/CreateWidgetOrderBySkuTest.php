<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\TestCase;

use Magento\AdvancedCheckout\Test\Fixture\AdvancedCheckoutWidget;
use Magento\Widget\Test\TestCase\AbstractCreateWidgetEntityTest;

/**
 * Steps:
 * 1. Login to the backend.
 * 2. Open Content > Widgets.
 * 3. Click Add Widget.
 * 4. Fill settings data for Order by Sku widget type according dataset.
 * 5. Click button Continue.
 * 6. Fill widget data according dataset.
 * 7. Perform all assertions.
 *
 * @group Widget
 * @ZephyrId MAGETWO-27916
 */
class CreateWidgetOrderBySkuTest extends AbstractCreateWidgetEntityTest
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Creation for New Instance of WidgetEntity.
     *
     * @param AdvancedCheckoutWidget $widget
     * @return void
     */
    public function test(AdvancedCheckoutWidget $widget)
    {
        // Steps
        $this->widgetInstanceIndex->open();
        $this->widgetInstanceIndex->getPageActionsBlock()->addNew();
        $this->widgetInstanceNew->getWidgetForm()->fill($widget);
        $this->widgetInstanceEdit->getPageActionsBlock()->save();
    }
}
