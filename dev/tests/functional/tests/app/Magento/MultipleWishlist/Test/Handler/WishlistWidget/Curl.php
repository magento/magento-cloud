<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Handler\WishlistWidget;

/**
 * Curl handler for creating wishlist widgetInstance/frontendApp.
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
            'Wish List Search' => 'wishlist_search',
        ],
    ];

    /**
     * Widget Instance Template.
     *
     * @var string
     */
    protected $widgetInstanceTemplate = 'widget/search.phtml';
}
