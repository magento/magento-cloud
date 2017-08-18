<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryInactiveNotInWishlist
 * Assert that product can't be added to Inactive GiftRegistry from Wishlist
 */
class AssertGiftRegistryInactiveNotInWishlist extends AbstractConstraint
{
    /**
     * Assert that product can not be added to inactive gift registry from Wishlist
     *
     * @param CatalogProductView $catalogProductView
     * @param CatalogProductSimple $product
     * @param GiftRegistry $giftRegistry
     * @param WishlistIndex $wishlistIndex
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CatalogProductSimple $product,
        GiftRegistry $giftRegistry,
        WishlistIndex $wishlistIndex,
        BrowserInterface $browser
    ) {
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->clickAddToWishlist();
        \PHPUnit_Framework_Assert::assertFalse(
            $wishlistIndex->getGiftRegistryWishlistBlock()->isGiftRegistryAvailable($giftRegistry),
            'Product can be added to inactive gift registry \'' . $giftRegistry->getTitle() . '\' from Wishlist.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product can not be added to inactive gift registry from Wishlist.';
    }
}
