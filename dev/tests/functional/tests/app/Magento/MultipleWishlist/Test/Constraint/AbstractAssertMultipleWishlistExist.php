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

/**
 * Abstract Class AbstractAssertMultipleWishlistExist
 * Assert that Wishlist exist or doesn't exist on 'My Account' page
 */
abstract class AbstractAssertMultipleWishlistExist extends AbstractConstraint
{
    /**
     * Assert that Wishlist exists on 'My Account' page
     *
     * @param CmsIndex $cmsIndex
     * @param WishlistIndex $wishlistIndex
     * @param MultipleWishlist $multipleWishlist
     * @param CustomerAccountIndex $customerAccountIndex
     * @return void
     */
    public function processAssert(
        CmsIndex $cmsIndex,
        WishlistIndex $wishlistIndex,
        MultipleWishlist $multipleWishlist,
        CustomerAccountIndex $customerAccountIndex
    ) {
        $cmsIndex->open()->getLinksBlock()->openLink('My Account');
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Wish List');
        $isPresent = $wishlistIndex->getManagementBlock()->isWishlistVisible($multipleWishlist->getName());
        $this->assert($isPresent);
    }

    /**
     * Assert wish list exists or doesn't exist
     *
     * @param bool $isPresent
     * @return void
     */
    abstract protected function assert($isPresent);
}
