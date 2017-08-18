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
 * 1. Enable Multiple Wishlist functionality.
 * 2. Create Customer Account.
 * 3. Create Multiple Wishlist.
 *
 * Test Flow:
 * 1. Login to frontend as a Customer.
 * 2. Navigate to: My Account > My Wishlist.
 * 3. Edit wishlist by clicking "Edit" link and according to attached data set.
 * 4. Perform appropriate assertions.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-27599
 */
class UpdateMultipleWishlistEntityTest extends AbstractMultipleWishlistEntityTest
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Update multiple wish list.
     *
     * @param MultipleWishlist $multipleWishlistOriginal
     * @param MultipleWishlist $multipleWishlist
     * @param Customer $customer
     * @return void
     */
    public function test(
        MultipleWishlist $multipleWishlistOriginal,
        MultipleWishlist $multipleWishlist,
        Customer $customer
    ) {
        // Preconditions
        $this->createWishlistSearchWidget();

        // Steps
        $multipleWishlistOriginal = $this->createMultipleWishlist($multipleWishlistOriginal, $customer);
        $this->openWishlistPage($customer);
        $this->wishlistIndex->getManagementBlock()->selectedWishlistByName(
            $multipleWishlistOriginal->getName()
        );
        $this->wishlistIndex->getManagementBlock()->editWishlist();
        $this->wishlistIndex->getBehaviourBlock()->fill($multipleWishlist);
        $this->wishlistIndex->getBehaviourBlock()->save();
    }

    /**
     * Disable multiple wish list in config and delete wish list search widget.
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
