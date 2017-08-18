<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\Cms\Test\Page\CmsPage;
use Magento\Customer\Test\Page\CustomerAccountLogout;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Customer\Test\Fixture\Customer;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\MultipleWishlist\Test\Page\Adminhtml\CustomerWishlistReport;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Simple products are created.
 * 3. Enable Multiple wishlist in configuration.
 * 4. Create Multiple wishlist.
 *
 * Steps:
 * 1. Login to backend as admin.
 * 2. Several created products are added to private Wish List.
 * 3. Several created products are added to public Wish List.
 * 4. Use the main menu "REPORTS" -> "Customers" -> "Wish Lists".
 * 5. Perform assertions.
 *
 * @group Reports
 * @ZephyrId MAGETWO-27346
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WishlistReportEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * Customer Wish List Report Page.
     *
     * @var CustomerWishlistReport
     */
    protected $customerWishlistReport;

    /**
     * CatalogProductView Page.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * My Wish Lists page.
     *
     * @var WishlistIndex
     */
    protected $wishlistIndex;

    /**
     * CustomerAccountLogout Page.
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * Index Page.
     *
     * @var CmsPage
     */
    protected $cmsPage;

    /**
     * Enable Multiple wishlist in configuration and create simple products.
     *
     * @param CatalogProductSimple $productSimple1
     * @param CatalogProductSimple $productSimple2
     * @return array
     */
    public function __prepare(
        CatalogProductSimple $productSimple1,
        CatalogProductSimple $productSimple2
    ) {
        $productSimple1->persist();
        $productSimple2->persist();

        return ['products' => [$productSimple1, $productSimple2]];
    }

    /**
     * Injection data.
     *
     * @param CatalogProductView $catalogProductView
     * @param WishlistIndex $wishlistIndex
     * @param CmsPage $cmsPage
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function __inject(
        CatalogProductView $catalogProductView,
        WishlistIndex $wishlistIndex,
        CmsPage $cmsPage,
        CustomerAccountLogout $customerAccountLogout
    ) {
        $this->catalogProductView = $catalogProductView;
        $this->wishlistIndex = $wishlistIndex;
        $this->customerAccountLogout = $customerAccountLogout;
        $this->cmsPage = $cmsPage;

        // TODO: Move set up configuration to "__prepare" method after fix bug MAGETWO-29331
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'multiple_wishlist_default']
        )->run();
    }

    /**
     * Add products to multiple wishlist.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param BrowserInterface $browser
     * @param array $products
     * @param array $wishlist
     * @return array
     */
    public function test(
        MultipleWishlist $multipleWishlist,
        BrowserInterface $browser,
        array $products,
        array $wishlist
    ) {
        // Precondition
        $multipleWishlist->persist();
        $customer = $multipleWishlist->getDataFieldConfig('customer_id')['source']->getCustomer();

        // Steps
        $this->loginCustomer($customer);
        foreach ($products as $key => $product) {
            $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
            $this->catalogProductView->getMultipleWishlistViewBlock()->addToMultipleWishlist($multipleWishlist);
            $this->wishlistIndex->getMultipleItemsBlock()->getItemProduct($product)
                ->fillProduct($wishlist[$key]);
            $this->wishlistIndex->getWishlistBlock()->clickUpdateWishlist();
            $this->cmsPage->getCmsPageBlock()->waitPageInit();
        }

        return ['customer' => $customer];
    }

    /**
     * Login customer.
     *
     * @param Customer $customer
     * @return void
     */
    protected function loginCustomer(Customer $customer)
    {
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
    }

    /**
     * Disable multiple wish list in config.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->customerAccountLogout->open();
        // TODO: Move set default configuration to "tearDownAfterClass" method after fix bug MAGETWO-29331
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => 'multiple_wishlist_default', 'rollback' => true]
        )->run();
    }
}
