<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;

/**
 * Preconditions:
 * 1. Enable Multiple Wishlist functionality.
 * 2. Create Customer Account.
 *
 * Test Flow:
 * 1. Login to frontend as a Customer.
 * 2. Navigate to: My Account > My Wishlist.
 * 3. Create wishlist.
 * 4. Delete wishlist.
 * 5. Perform appropriate assertions.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-27253
 */
class DeleteMultipleWishlistEntityTest extends AbstractMultipleWishlistEntityTest
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Delete Multiple Wishlist Entity.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param Customer $customer
     * @param string $isCreateMultipleWishlist
     * @return void
     */
    public function test(MultipleWishlist $multipleWishlist, Customer $customer, $isCreateMultipleWishlist)
    {
        // Steps
        if ($isCreateMultipleWishlist == 'No') {
            return;
        }
        $multipleWishlist = $this->createMultipleWishlist($multipleWishlist, $customer);
        $this->openWishlistPage($customer);
        $this->wishlistIndex->getManagementBlock()->selectedWishlistByName($multipleWishlist->getName());
        $this->wishlistIndex->getManagementBlock()->removeWishlist();
    }
}
