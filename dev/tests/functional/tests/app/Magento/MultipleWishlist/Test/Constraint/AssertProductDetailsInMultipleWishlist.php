<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\MultipleWishlist\Test\Fixture\MultipleWishlist;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Wishlist\Test\Constraint\AbstractAssertWishlistProductDetails;

/**
 * Assert that the correct option details are displayed on the "See Details" tooltip.
 */
class AssertProductDetailsInMultipleWishlist extends AbstractAssertWishlistProductDetails
{
    /**
     * Assert that the correct option details are displayed on the "See Details" tooltip.
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param WishlistIndex $wishlistIndex
     * @param InjectableFixture $product
     * @param FixtureFactory $fixtureFactory
     * @param MultipleWishlist $multipleWishlist
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        WishlistIndex $wishlistIndex,
        InjectableFixture $product,
        FixtureFactory $fixtureFactory,
        MultipleWishlist $multipleWishlist
    ) {
        $cmsIndex->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Wish List');
        $cmsIndex->getCmsPageBlock()->waitPageInit();
        $wishlistIndex->getManagementBlock()->selectedWishlistByName($multipleWishlist->getName());
        $this->assertProductDetails($wishlistIndex, $product, $fixtureFactory);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Expected product options are equal to actual.";
    }
}
