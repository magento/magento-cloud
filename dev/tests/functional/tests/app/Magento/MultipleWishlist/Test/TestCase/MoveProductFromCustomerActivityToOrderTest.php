<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Mtf\TestCase\Injectable;
use Magento\MultipleWishlist\Test\TestStep\CreateOrderForCustomerMultipleWishlistStep;

/**
 * Preconditions:
 * 1. Create Product.
 * 2. Enable Multiple Wishlist functionality.
 * 3. Create Customer Account.
 * 4. Create Wishlist.
 *
 * Steps:
 * 1. Login to frontend as a Customer.
 * 2. Navigate to created product.
 * 3. Select created wishlist and add product to it.
 * 4. Go to Customers account on backend.
 * 5. Press 'Create Order' button.
 * 6. Choose your wishlist in dropdown in Customer's Activities section.
 * 7. Mark checkbox near product.
 * 8. Click button Update Changes.
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-29530
 */
class MoveProductFromCustomerActivityToOrderTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Injection data.
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
     * Move product from customer activity to order on backend.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param string $products
     * @param string $duplicate
     * @param string $qtyToMove
     * @return array
     */
    public function test(MultipleWishlist $multipleWishlist, $products, $duplicate, $qtyToMove)
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
        $loginCustomer = $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        );
        $loginCustomer->run();

        $addProductToMultiplewishlist = $this->objectManager->create(
            \Magento\MultipleWishlist\Test\TestStep\AddProductToMultipleWishlistStep::class,
            ['product' => $product, 'duplicate' => $duplicate, 'multipleWishlist' => $multipleWishlist]
        );
        $addProductToMultiplewishlist->run();

        $createOrderForCustomerMultipleWishlist = $this->objectManager->create(
            CreateOrderForCustomerMultipleWishlistStep::class,
            [
                'customer' => $customer,
                'multipleWishlist' => $multipleWishlist,
                'product' => $product,
                'qtyToMove' => $qtyToMove
            ]
        );
        $createOrderForCustomerMultipleWishlist->run();

        return ['products' => [$product]];
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
