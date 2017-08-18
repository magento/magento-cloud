<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Wishlist\Test\Page\WishlistShare;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create Customer Account
 * 2. Create custom wishlist
 * 3. Create product
 *
 * Steps:
 * 1. Log in to Frontend as a Customer
 * 2. Add product to custom Wish List
 * 3. Click "Share Wish List" button
 * 4. Fill in all data according to data set
 * 5. Click "Share Wishlist" button
 * 6. Perform all assertions
 *
 * @group Multiple_Wishlists
 * @ZephyrId MAGETWO-28982
 */
class ShareMultipleWishlistTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Catalog Product View Page.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Wishlist index Page.
     *
     * @var WishlistIndex
     */
    protected $wishlistIndex;

    /**
     * Wishlist Share Page.
     *
     * @var WishlistShare
     */
    protected $wishlistShare;

    /**
     * Browser object.
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * Enable Multiple wishlist in configuration.
     *
     * @return void
     */
    public function __prepare()
    {
        $setupConfig = $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'multiple_wishlist_default']
        );
        $setupConfig->run();
    }

    /**
     * Injection data.
     *
     * @param CatalogProductView $catalogProductView
     * @param WishlistIndex $wishlistIndex
     * @param WishlistShare $wishlistShare
     * @param BrowserInterface $browser
     * @return void
     */
    public function __inject(
        CatalogProductView $catalogProductView,
        WishlistIndex $wishlistIndex,
        WishlistShare $wishlistShare,
        BrowserInterface $browser
    ) {
        $this->catalogProductView = $catalogProductView;
        $this->wishlistIndex = $wishlistIndex;
        $this->wishlistShare = $wishlistShare;
        $this->browser = $browser;
    }

    /**
     * Share Multiple Wish list.
     *
     * @param CatalogProductSimple $product
     * @param MultipleWishlist $multipleWishlist
     * @param array $sharingInfo
     * @return void
     */
    public function test(
        CatalogProductSimple $product,
        MultipleWishlist $multipleWishlist,
        array $sharingInfo
    ) {
        // Preconditions
        $multipleWishlist->persist();
        $product->persist();
        $customer = $multipleWishlist->getDataFieldConfig('customer_id')['source']->getCustomer();

        // Steps
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $this->browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getMultipleWishlistViewBlock()->addToMultipleWishlist($multipleWishlist);
        $this->wishlistIndex->getWishlistBlock()->clickShareWishList();
        $this->wishlistShare->getSharingInfoForm()->fillForm($sharingInfo);
        $this->wishlistShare->getSharingInfoForm()->shareWishlist();
    }

    /**
     * Clear data after test.
     *
     * @return void
     */
    public function tearDown()
    {
        $setupConfig = $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'multiple_wishlist_default', 'rollback' => true]
        );
        $setupConfig->run();
    }
}
