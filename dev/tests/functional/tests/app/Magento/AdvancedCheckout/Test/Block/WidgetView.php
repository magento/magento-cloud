<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Block;

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
    protected $widgetSelectors = ['orderBySku' => '/descendant-or-self::div[contains(.,"%s")]'];
}
