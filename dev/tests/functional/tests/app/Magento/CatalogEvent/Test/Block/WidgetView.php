<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
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
