<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\TestCase;

use Magento\CatalogEvent\Test\Fixture\CatalogEventWidget;
use Magento\Widget\Test\TestCase\AbstractCreateWidgetEntityTest;

/**
 * Steps:
 * 1. Login to the backend
 * 2. Open Content > Widgets
 * 3. Click Add Widget
 * 4. Fill settings data for Catalog Event Carousel widget type according dataset
 * 5. Click button Continue
 * 6. Fill widget data according dataset
 * 7. Perform all assertions
 *
 * @group Widget
 * @ZephyrId MAGETWO-27916
 */
class CreateWidgetCatalogEventCarouselTest extends AbstractCreateWidgetEntityTest
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Delete all Catalog Events on backend.
     *
     * @return void
     */
    public function __prepare()
    {
        $this->objectManager->create(\Magento\CatalogEvent\Test\TestStep\DeleteAllCatalogEventsStep::class)->run();
    }

    /**
     * Creation for New Instance of WidgetEntity
     *
     * @param CatalogEventWidget $widget
     * @return void
     */
    public function test(CatalogEventWidget $widget)
    {
        // Steps
        $this->widgetInstanceIndex->open();
        $this->widgetInstanceIndex->getPageActionsBlock()->addNew();
        $this->widgetInstanceNew->getWidgetForm()->fill($widget);
        $this->widgetInstanceEdit->getPageActionsBlock()->save();
    }
}
