<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\GroupedProduct\Test\Fixture\GroupedProduct;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\ObjectManager;
use Magento\Mtf\TestCase\Injectable;

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
 * @group Multiple_Wishlists_(CS)
 * @ZephyrId MAGETWO-29530
 */
class MoveProductFromCustomerActivityToOrderTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'CS';
    /* end tags */

    /**
     * CustomerIndex page.
     *
     * @var CustomerIndex
     */
    protected $customerIndex;

    /**
     * CustomerIndexEdit page.
     *
     * @var CustomerIndexEdit
     */
    protected $customerIndexEdit;

    /**
     * OrderCreateIndex page.
     *
     * @var OrderCreateIndex
     */
    protected $orderCreateIndex;

    /**
     * Injection data.
     *
     * @param CustomerIndex $customerIndex
     * @param CustomerIndexEdit $customerIndexEdit
     * @param OrderCreateIndex $orderCreateIndex
     * @return void
     */
    public function __inject(
        CustomerIndex $customerIndex,
        CustomerIndexEdit $customerIndexEdit,
        OrderCreateIndex $orderCreateIndex
    ) {
        $this->customerIndex = $customerIndex;
        $this->customerIndexEdit = $customerIndexEdit;
        $this->orderCreateIndex = $orderCreateIndex;

        // TODO: Move set up configuration to "__prepare" method after fix bug MAGETWO-29331
        $this->objectManager->create(
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
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
            'Magento\Catalog\Test\TestStep\CreateProductsStep',
            ['products' => $products]
        );
        $product = $createProductsStep->run()['products'][0];

        // Steps
        $loginCustomer = $this->objectManager->create(
            'Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep',
            ['customer' => $customer]
        );
        $loginCustomer->run();

        $addProductToMultiplewishlist = $this->objectManager->create(
            'Magento\MultipleWishlist\Test\TestStep\AddProductToMultipleWishlistStep',
            ['product' => $product, 'duplicate' => $duplicate, 'multipleWishlist' => $multipleWishlist]
        );
        $addProductToMultiplewishlist->run();

        $this->customerIndex->open();
        $this->customerIndex->getCustomerGridBlock()->searchAndOpen(['email' => $customer->getEmail()]);
        $this->customerIndexEdit->getPageActionsBlock()->createOrder();
        $this->orderCreateIndex->getStoreBlock()->selectStoreView();
        $this->orderCreateIndex->getMultipleWishlistBlock()->selectWishlist($multipleWishlist->getName());
        $wishlistItemsBlock = $this->orderCreateIndex->getMultipleWishlistBlock()->getWishlistItemsBlock();
        $wishlistItemsBlock->selectItemToAddToOrder($product, $qtyToMove);
        if (!$product instanceof GroupedProduct) {
            $this->orderCreateIndex->getCustomerActivitiesBlock()->updateChanges();
        } else {
            $this->orderCreateIndex->getConfigureProductBlock()->clickOk();
        }

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
            'Magento\Config\Test\TestStep\SetupConfigurationStep',
            ['configData' => 'multiple_wishlist_default', 'rollback' => true]
        )->run();
    }
}
