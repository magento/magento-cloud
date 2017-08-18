<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\MultipleWishlist\Test\Page\SearchResult;

/**
 * Class AssertMultipleWishlistIsPublic
 * Assert wish list is public
 */
class AssertMultipleWishlistIsPublic extends AbstractAssertMultipleWishlistState
{
    /**
     * Public notice type
     *
     * @var string
     */
    protected $noticeType = 'public';

    /**
     * Assert that Wishlist can be find by another Customer (or guest) via "Wishlist Search"
     *
     * @param SearchResult $searchResult
     * @param MultipleWishlist $multipleWishlist
     * @return void
     */
    protected function assert(SearchResult $searchResult, MultipleWishlist $multipleWishlist)
    {
        \PHPUnit_Framework_Assert::assertTrue(
            $searchResult->getWishlistSearchResultBlock()->isWishlistVisibleInGrid($multipleWishlist->getName()),
            'Multiple wish list is not public.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Multiple wish list is public.';
    }
}
