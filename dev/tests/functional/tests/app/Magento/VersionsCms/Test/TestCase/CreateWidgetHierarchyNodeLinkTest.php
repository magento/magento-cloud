<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\TestCase;

use Magento\VersionsCms\Test\Fixture\VersionsCmsWidget;
use Magento\Widget\Test\TestCase\AbstractCreateWidgetEntityTest;

/**
 * Steps:
 * 1. Login to the backend.
 * 2. Open Content > Widgets.
 * 3. Click Add Widget.
 * 4. Fill settings data for Hierarchy Node Link widget type according dataset.
 * 5. Click button Continue.
 * 6. Fill widget data according dataset.
 * 7. Perform all assertions.
 *
 * @group Widget
 * @ZephyrId MAGETWO-27916
 */
class CreateWidgetHierarchyNodeLinkTest extends AbstractCreateWidgetEntityTest
{
    /* tags */
    const MVP = 'yes';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Creation for New Instance of WidgetEntity.
     *
     * @param VersionsCmsWidget $widget
     * @return void
     */
    public function test(VersionsCmsWidget $widget)
    {
        // Steps
        $this->widgetInstanceIndex->open();
        $this->widgetInstanceIndex->getPageActionsBlock()->addNew();
        $this->widgetInstanceNew->getWidgetForm()->fill($widget);
        $this->widgetInstanceEdit->getPageActionsBlock()->save();
    }
}
