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
 * 1. Enable Multiple wish list in config.
 * 2. Register new customer.
 * 3. Create one custom wish list.
 * 4. Add product with qty defined in dataset to default wish list.
 *
 * Steps:
 * 1. Login to the Frontend as a customer.
 * 2. Open default wish list.
 * 3. Set qtyToMove and move it to custom wish list.
 * 4. Perform assertions.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-28820
 */
class MoveProductToAnotherWishlistEntityTest extends AbstractActionProductToAnotherWishlistTest
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Multiple wish list move action.
     *
     * @var string
     */
    protected $action = 'move';

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
