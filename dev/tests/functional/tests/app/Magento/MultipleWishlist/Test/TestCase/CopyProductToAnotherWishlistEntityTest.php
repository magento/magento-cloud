<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;

/**
 * Test Creation for CopyProductToAnotherWishList
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Enable Multiple wish list in config.
 * 2. Create customer.
 * 3. Create one multiple wish list.
 * 4. Add product with qty defined in dataset to default wish list.
 *
 * Steps:
 * 1. Log in on frontend.
 * 2. Open default wish list.
 * 3. Check product.
 * 4. Set qtyToCopy and copy it to another wish list.
 * 5. Perform assertions.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-29640
 */
class CopyProductToAnotherWishlistEntityTest extends AbstractActionProductToAnotherWishlistTest
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Multiple wish list copy action.
     *
     * @var string
     */
    protected $action = 'copy';

    /**
     * Run Move To Another Wishlist test.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param Customer $customer
     * @param string $product
     * @param int $qty
     * @param int $qtyToAction
     * @return array
     */
    public function test(MultipleWishlist $multipleWishlist, Customer $customer, $product, $qty, $qtyToAction)
    {
        // Preconditions
        $this->createMultipleWishlist($multipleWishlist, $customer);
        $product = $this->createProduct($product, $qty);
        $this->loginCustomer($customer);
        $this->addProductToWishlist($product);

        // Steps
        $this->actionProductToAnotherWishlist($multipleWishlist, $product, $qtyToAction);

        return ['product' => $product, 'typeAction' => $this->action];
    }
}
