<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\TestCase;

use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Abstract Class for multiple wish list entity tests.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractMultipleWishlistEntityTest extends Injectable
{
    /**
     * Fixture factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Cms index page.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Customer account index page.
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Multiple wish list index page.
     *
     * @var WishlistIndex
     */
    protected $wishlistIndex;

    /**
     * Catalog product view page.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Admin cache page.
     *
     * @var AdminCache
     */
    protected $cachePage;

    /**
     * Browser object.
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @param Customer $customer
     * @param Category $category
     * @param AdminCache $cachePage
     * @param BrowserInterface $browser
     * @return array
     */
    public function __prepare(
        FixtureFactory $fixtureFactory,
        Customer $customer,
        Category $category,
        AdminCache $cachePage,
        BrowserInterface $browser
    ) {
        $this->cachePage = $cachePage;
        $this->browser = $browser;
        $this->fixtureFactory = $fixtureFactory;

        $customer->persist();
        $category->persist();

        return ['category' => $category, 'customer' => $customer];
    }

    /**
     * Injection data.
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param WishlistIndex $wishlistIndex
     * @param CatalogProductView $catalogProductView
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        WishlistIndex $wishlistIndex,
        CatalogProductView $catalogProductView
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->wishlistIndex = $wishlistIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->catalogProductView = $catalogProductView;

        // TODO: Move set up configuration to "__prepare" method after fix bug MAGETWO-29331
        $this->setupConfiguration('multiple_wishlist_default');
    }

    /**
     * Create multiple wish list.
     *
     * @param MultipleWishlist $multipleWishlist
     * @param Customer $customer
     * @return MultipleWishlist
     */
    protected function createMultipleWishlist(MultipleWishlist $multipleWishlist, Customer $customer)
    {
        $data = $multipleWishlist->getData();
        $data['customer_id'] = ['customer' => $customer];
        $multipleWishlist = $this->fixtureFactory->createByCode('multipleWishlist', ['data' => $data]);
        $multipleWishlist->persist();

        return $multipleWishlist;
    }

    /**
     * Add wish list search widget.
     *
     * @return void
     */
    protected function createWishlistSearchWidget()
    {
        $wishlistSearch = $this->fixtureFactory->createByCode('wishlistWidget', ['dataset' => 'wishlist_search']);
        $wishlistSearch->persist();
        $this->cachePage->open()->getActionsBlock()->flushMagentoCache();
    }

    /**
     * Log in customer on frontend.
     *
     * @param Customer $customer
     * @return void
     */
    protected function loginCustomer(Customer $customer)
    {
        $customerLogin = $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        );
        $customerLogin->run();
    }

    /**
     * Open wish list page.
     *
     * @param Customer $customer
     * @return void
     */
    protected function openWishlistPage(Customer $customer)
    {
        $this->loginCustomer($customer);
        $this->cmsIndex->getLinksBlock()->openLink('My Account');
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Wish List');
    }

    /**
     * Setup configuration.
     *
     * @param string $configData
     * @param bool $rollback
     * @return void
     */
    public function setupConfiguration($configData, $rollback = false)
    {
        $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $configData, 'rollback' => $rollback]
        )->run();
    }

    /**
     * Disable multiple wish list in config.
     *
     * @return void
     */
    public function tearDown()
    {
        // TODO: Move set default configuration to "tearDownAfterClass" method after fix bug MAGETWO-29331
        $this->setupConfiguration('multiple_wishlist_default', true);
    }
}
