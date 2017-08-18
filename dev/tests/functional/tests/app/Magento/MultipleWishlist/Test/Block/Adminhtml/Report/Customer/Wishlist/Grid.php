<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Adminhtml\Report\Customer\Wishlist;

/**
 * Class Grid
 * Customer Wish List Report Grid
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Filters array mapping
     *
     * @var array
     */
    protected $filters = [
        'wishlist_name' => [
            'selector' => 'input[name="wishlist_name"]',
        ],
        'visibility' => [
            'selector' => 'select[name="visibility"]',
            'input' => 'select',
        ],
        'item_comment' => [
            'selector' => 'input[name="item_comment"]',
        ],
    ];

    /**
     * Check if specific row exists in grid
     *
     * @param array $filter
     * @param bool $isSearchable
     * @param bool $isStrict
     * @return bool
     */
    public function isRowVisible(array $filter, $isSearchable = false, $isStrict = true)
    {
        $this->search(['wishlist_name' => $filter['wishlist_name']]);
        return parent::isRowVisible($filter, $isSearchable, $isStrict);
    }
}
