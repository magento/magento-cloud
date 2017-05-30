<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Block\Adminhtml\Promo\Catalog\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Grid;

/**
 * Class BannersGrid
 * Banners grid on Catalog Price Rules page
 */
class BannersGrid extends Grid
{
    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'banner_name' => [
            'selector' => 'input[name="banner_name"]',
        ],
    ];
}
