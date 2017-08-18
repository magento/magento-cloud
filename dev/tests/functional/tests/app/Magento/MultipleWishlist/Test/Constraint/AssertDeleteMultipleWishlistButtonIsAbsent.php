<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertDeleteMultipleWishlistButtonIsAbsent
 * Assert that there is no "Delete Wishlist" button for Customer
 */
class AssertDeleteMultipleWishlistButtonIsAbsent extends AbstractConstraint
{
    /**
     * Assert that there is no "Delete Wish List" button for Customer
     *
     * @param WishlistIndex $wishlistIndex
     * @return void
     */
    public function processAssert(WishlistIndex $wishlistIndex)
    {
        $wishlistIndex->open();
        \PHPUnit_Framework_Assert::assertFalse(
            $wishlistIndex->getManagementBlock()->isRemoveButtonVisible(),
            '"Delete Wish List" button is visible for Customer.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return '"Delete Wish List" button is not visible for Customer.';
    }
}
