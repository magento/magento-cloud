<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Adminhtml\Giftregistry;

/**
 * Class Grid
 * GiftRegistryType grid
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Grid filters' selectors
     *
     * @var array
     */
    protected $filters = [
        'label' => [
            'selector' => 'input[name="label"]',
        ],
    ];

    /**
     * Locator value for td with role name
     *
     * @var string
     */
    protected $editLink = '[data-column="code"]';
}
