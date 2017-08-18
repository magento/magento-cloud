<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Customer\Test\Fixture\Customer;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;

/**
 * Preconditions:
 * 1. Enable Multiple Wishlist functionality & set "Number of Multiple Wish Lists = 3.
 * 2. Create Customer Account.
 *
 * Steps:
 * 1. Login to frontend as a Customer.
 * 2. Navigate to: My Account > My Wishlist.
 * 3. Start creating Wishlist.
 * 4. Fill in data according to attached data set.
 * 5. Perform appropriate assertions.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-27157
 */
class CreateMultipleWishlistEntityTest extends AbstractMultipleWishlistEntityTest
{
    /* tags */
    const MVP = 'no';
    const STABLE = 'no';
    /* end tags */

    /**
     * Create new multiple wish list.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param Customer $customer
     * @return void
     */
    public function test(MultipleWishlist $multipleWishlist, Customer $customer)
    {
        // Preconditions
        $this->createWishlistSearchWidget();

        // Steps
        $this->openWishlistPage($customer);
        $this->wishlistIndex->getManagementBlock()->clickCreateNewWishlist();
        $this->wishlistIndex->getBehaviourBlock()->fill($multipleWishlist);
        $this->wishlistIndex->getBehaviourBlock()->save();
    }

    /**
     * Inactive multiple wish list in config and delete wish list search widget.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $this->objectManager->create(\Magento\Widget\Test\TestStep\DeleteAllWidgetsStep::class)->run();
        $this->cachePage->open()->getActionsBlock()->flushMagentoCache();
    }
}
