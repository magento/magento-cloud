<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogEvent\Test\Block;

use Magento\Widget\Test\Fixture\Widget;

/**
 * Widget catalog event block on the frontend
 */
class WidgetView extends \Magento\Widget\Test\Block\WidgetView
{
    /**
     * Widgets selectors
     *
     * @var array
     */
    protected $widgetSelectors = [
        'catalogEventsCarousel' => '(.//*/a/span[contains(.,"%s")])[last()]',
    ];
}
