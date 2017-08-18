<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\Handler\AdvancedCheckoutWidget;

/**
 * Curl handler for creating widgetInstance/frontendApp.
 */
class Curl extends \Magento\Widget\Test\Handler\Widget\Curl
{
    /**
     * Mapping values for data.
     *
     * @var array
     */
    protected $additionalMappingData = [
        'code' => [
            'Order by SKU' => 'order_by_sku',
        ],
    ];

    /**
     * Widget Instance Template.
     *
     * @var string
     */
    protected $widgetInstanceTemplate = 'widget/sku.phtml';
}
