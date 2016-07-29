<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Block;

use Magento\Widget\Test\Fixture\Widget;

/**
 * Widget block on the frontend
 */
class WidgetView extends \Magento\Widget\Test\Block\WidgetView
{
    /**
     * Widgets selectors
     *
     * @var array
     */
    protected $widgetSelectors = ['bannerRotator' => '/descendant-or-self::div[contains(.,"%s")]'];
}
