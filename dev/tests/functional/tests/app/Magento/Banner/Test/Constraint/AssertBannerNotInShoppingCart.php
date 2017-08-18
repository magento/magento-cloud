<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Banner\Test\Constraint;

use Magento\Banner\Test\Fixture\Banner;
use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Category\CatalogCategoryView;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertBannerInShoppingCart
 * Check that banner is absent on Shopping Cart page
 */
class AssertBannerNotInShoppingCart extends AbstractConstraint
{
    /**
     * Assert that banner is absent on Shopping Cart page
     *
     * @param CatalogProductSimple $product
     * @param CatalogProductView $pageCatalogProductView
     * @param CmsIndex $cmsIndex
     * @param CatalogCategoryView $catalogCategoryView
     * @param CheckoutCart $pageCheckoutCart
     * @param Banner $banner
     * @param Customer $customer
     * @return void
     */
    public function processAssert(
        CatalogProductSimple $product,
        CatalogProductView $pageCatalogProductView,
        CmsIndex $cmsIndex,
        CatalogCategoryView $catalogCategoryView,
        CheckoutCart $pageCheckoutCart,
        Banner $banner,
        Customer $customer = null
    ) {
        if ($customer !== null) {
            $this->objectManager->create(
                \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
                ['customer' => $customer]
            )->run();
        } else {
            $cmsIndex->open();
        }
        $cmsIndex->getTopmenu()->selectCategoryByName($product->getCategoryIds()[0]);
        $catalogCategoryView->getListProductBlock()->getProductItem($product)->open();
        $pageCatalogProductView->getViewBlock()->clickAddToCartButton();
        $pageCatalogProductView->getMessagesBlock()->waitSuccessMessage();
        $pageCheckoutCart->open();
        \PHPUnit_Framework_Assert::assertFalse(
            $pageCheckoutCart->getBannerCartBlock()->checkWidgetBanners($banner, $customer),
            'Banner is presents on Shopping Cart'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return "Banner is absent on Shopping Cart.";
    }
}
