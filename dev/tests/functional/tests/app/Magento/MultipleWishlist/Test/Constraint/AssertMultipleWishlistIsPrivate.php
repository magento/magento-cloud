<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\MultipleWishlist\Test\Page\SearchResult;

/**
 * Class AssertMultipleWishlistIsPrivate
 * Assert wish list is private
 */
class AssertMultipleWishlistIsPrivate extends AbstractAssertMultipleWishlistState
{
    /**
     * Private notice type
     *
     * @var string
     */
    protected $noticeType = 'private';

    /**
     * Assert that Wishlist can't be find by another Customer (or guest) via "Wishlist Search"
     *
     * @param SearchResult $searchResult
     * @param MultipleWishlist $multipleWishlist
     * @return void
     */
    protected function assert(SearchResult $searchResult, MultipleWishlist $multipleWishlist)
    {
        \PHPUnit_Framework_Assert::assertFalse(
            $searchResult->getWishlistSearchResultBlock()->isWishlistVisibleInGrid($multipleWishlist->getName()),
            'Multiple wish list is not private.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Multiple wish list is private.';
    }
}
