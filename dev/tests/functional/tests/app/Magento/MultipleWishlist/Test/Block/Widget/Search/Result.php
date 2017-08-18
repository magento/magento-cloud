<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Block\Widget\Search;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class Result
 * Wish list search result block
 */
class Result extends Block
{
    /**
     * Search row css selector
     *
     * @var string
     */
    protected $searchRow = './/td[contains(@class,"col list")][.="%s"]';

    /**
     * Wish list is visible in grid
     *
     * @param string $name
     * @return bool
     */
    public function isWishlistVisibleInGrid($name)
    {
        return $this->_rootElement->find(sprintf($this->searchRow, $name), Locator::SELECTOR_XPATH)->isVisible();
    }
}
