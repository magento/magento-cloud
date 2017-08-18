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
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Class AssertProductInCustomWishlist
 * Assert that product is present in custom wishlist
 */
class AssertProductInCustomWishlist extends AbstractConstraint
{
    /**
     * Assert that product is present in custom wishlist
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param MultipleWishlist $multipleWishlist
     * @param WishlistIndex $wishlistIndex
     * @param InjectableFixture $product
     * @param int $qtyToAction
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        MultipleWishlist $multipleWishlist,
        WishlistIndex $wishlistIndex,
        InjectableFixture $product,
        $qtyToAction
    ) {
        $cmsIndex->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Wish List');
        $cmsIndex->getCmsPageBlock()->waitPageInit();
        $wishlistIndex->getManagementBlock()->selectedWishlistByName($multipleWishlist->getName());
        $cmsIndex->getCmsPageBlock()->waitPageInit();
        $formData = $wishlistIndex->getMultipleItemsBlock()->getItemProduct($product)->getWishlistData($qtyToAction);
        $actualQuantity = ($qtyToAction == '-') ? '-' : $formData['qty'];

        \PHPUnit_Framework_Assert::assertEquals(
            $qtyToAction,
            $actualQuantity,
            'Actual quantity of ' . $product->getName() . ' in custom wishlist doesn\'t match to expected.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Product with correct quantity is present in custom wishlist';
    }
}
