<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\MultipleWishlist\Test\Constraint;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Class AssertProductInDefaultWishlist
 * Assert that product and quantity is present in default wishlist
 */
class AssertProductInDefaultWishlist extends AbstractConstraint
{
    /**
     * Assert that product and quantity is present in default wishlist
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param WishlistIndex $wishlistIndex
     * @param InjectableFixture $product
     * @param string $qty
     * @param int $qtyToAction
     * @param string $typeAction
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        WishlistIndex $wishlistIndex,
        InjectableFixture $product,
        $qty,
        $qtyToAction,
        $typeAction
    ) {
        $cmsIndex->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Wish List');
        $cmsIndex->getCmsPageBlock()->waitPageInit();
        $formData = $wishlistIndex->getMultipleItemsBlock()->getItemProduct($product)->getWishlistData($qty);
        $actualQuantity = ($qty == '-') ? '-' : $formData['qty'];
        $expectedQuantity = ($typeAction == 'move') ? $qty - $qtyToAction : $qty;

        \PHPUnit_Framework_Assert::assertEquals(
            $expectedQuantity,
            $actualQuantity,
            'Actual quantity of ' . $product->getName() . ' in default wishlist doesn\'t match to expected.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Product with correct quantity is present in default wishlist';
    }
}
