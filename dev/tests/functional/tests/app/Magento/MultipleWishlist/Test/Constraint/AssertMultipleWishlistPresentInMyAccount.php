<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

/**
 * Class AssertMultipleWishlistPresentInMyAccount
 * Assert that Wishlist exists on 'My Account' page
 */
class AssertMultipleWishlistPresentInMyAccount extends AbstractAssertMultipleWishlistExist
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Assert wish list is present
     *
     * @param bool $isPresent
     * @return void
     */
    protected function assert($isPresent)
    {
        \PHPUnit_Framework_Assert::assertTrue($isPresent, 'Multiple wish list is not exist on "My Account" page.');
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Multiple wish list exists on "My Account" page.';
    }
}
