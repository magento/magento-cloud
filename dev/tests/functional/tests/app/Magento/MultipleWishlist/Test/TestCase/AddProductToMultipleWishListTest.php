<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create Product
 * 2. Enable Multiple Wishlist functionality
 * 3. Create Customer Account
 * 4. Create Wishlist
 *
 * Steps:
 * 1. Login to frontend as a customer
 * 2. Navigate to created product
 * 3. Select created wishlist and add product to it
 * 4. Perform appropriate assertions.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-29044
 */
class AddProductToMultipleWishListTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Enable Multiple wishlist in configuration.
     *
     * @return void
     */
    public function __inject()
    {
        // TODO: Move set up configuration to "__prepare" method after fix bug MAGETWO-29331
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'multiple_wishlist_default']
        )->run();
    }

    /**
     * Add Product to Multiple Wish list.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param string $products
     * @param string $duplicate
     * @return array
     */
    public function test(MultipleWishlist $multipleWishlist, $products, $duplicate)
    {
        // Preconditions
        $multipleWishlist->persist();
        $customer = $multipleWishlist->getDataFieldConfig('customer_id')['source']->getCustomer();
        $createProductsStep = $this->objectManager->create(
            \Magento\Catalog\Test\TestStep\CreateProductsStep::class,
            ['products' => $products]
        );
        $product = $createProductsStep->run()['products'][0];

        // Steps
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();

        $this->objectManager->create(
            \Magento\MultipleWishlist\Test\TestStep\AddProductToMultipleWishlistStep::class,
            ['product' => $product, 'duplicate' => $duplicate, 'multipleWishlist' => $multipleWishlist]
        )->run();

        return [
            'product' => $product,
            'multipleWishlist' => $multipleWishlist,
            'customer' => $customer,
        ];
    }

    /**
     * Disable multiple wish list in config.
     *
     * @return void
     */
    public function tearDown()
    {
        // TODO: Move set default configuration to "tearDownAfterClass" method after fix bug MAGETWO-29331
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'multiple_wishlist_default', 'rollback' => true]
        )->run();
    }
}
