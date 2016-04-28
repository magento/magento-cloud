<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\VersionsCms\Test\Block\Adminhtml\Page\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Grid;

/**
 * Class VersionsGrid
 * Cms page versions grid block
 */
class VersionsGrid extends Grid
{
    /**
     * Locator value for link in version grid
     *
     * @var string
     */
    protected $editLink = 'td[class*=col-label]';

    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'label' => [
            'selector' => 'select[name="label"]',
            'input' => 'select',
        ],
        'owner' => [
            'selector' => 'select[name="owner"]',
            'input' => 'select',
        ],
        'access_level' => [
            'selector' => 'select[name="access_level"]',
            'input' => 'select',
        ],
        'quantity' => [
            'selector' => 'input[name="revisions[from]"]',
        ],
    ];
}
