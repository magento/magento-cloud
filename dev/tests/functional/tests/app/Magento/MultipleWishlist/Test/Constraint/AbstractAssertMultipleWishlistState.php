<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\Catalog\Test\Fixture\Category;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\MultipleWishlist\Test\Page\SearchResult;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that Wish list can be or can't be find by another Customer (or guest) via "Wishlist Search".
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractAssertMultipleWishlistState extends AbstractConstraint
{
    /**
     * Notice type.
     *
     * @var string
     */
    protected $noticeType;

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
     * Assert that Wishlist can be or can't be find by another Customer (or guest) via "Wishlist Search".
     *
     * @param MultipleWishlist $multipleWishlist
     * @param CmsIndex $cmsIndex
     * @param Category $category
     * @param CatalogCategoryView $catalogCategoryView
     * @param Customer $customer
     * @param SearchResult $searchResult
     * @param CustomerAccountIndex $customerAccountIndex
     * @param WishlistIndex $wishlistIndex
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function processAssert(
        MultipleWishlist $multipleWishlist,
        CmsIndex $cmsIndex,
        Category $category,
        CatalogCategoryView $catalogCategoryView,
        Customer $customer,
        SearchResult $searchResult,
        CustomerAccountIndex $customerAccountIndex,
        WishlistIndex $wishlistIndex
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->loginCustomer($customer);
        $cmsIndex->open()->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Wish List');
        $wishlistIndex->getManagementBlock()->selectedWishlistByName($multipleWishlist->getName());
        \PHPUnit_Framework_Assert::assertTrue(
            $wishlistIndex->getManagementBlock()->isNoticeTypeVisible($this->noticeType),
            'Notice type is not correct.'
        );

        $this->logout();
        $cmsIndex->open()->getTopmenu()->selectCategoryByName($category->getName());
        $catalogCategoryView->getWishlistSearchBlock()->searchByEmail($customer->getEmail());
        $this->assert($searchResult, $multipleWishlist);
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
     * Logout.
     *
     * @return void
     */
    protected function logout()
    {
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
    }

    /**
     * Assert wish list is public.
     *
     * @param SearchResult $searchResult
     * @param MultipleWishlist $multipleWishlist
     * @return void
     */
    abstract protected function assert(SearchResult $searchResult, MultipleWishlist $multipleWishlist);
}
