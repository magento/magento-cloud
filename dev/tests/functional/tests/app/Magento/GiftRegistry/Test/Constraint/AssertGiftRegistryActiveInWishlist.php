<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\GiftRegistry\Test\Page\GiftRegistryItems;
use Magento\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryActiveInWishlist
 * Assert that any product can be added to appropriate active GiftRegistry from Wishlist
 */
class AssertGiftRegistryActiveInWishlist extends AbstractConstraint
{
    /**
     * Success message after gift registry has been added
     */
    const SUCCESS_MESSAGE = 'The wish list item has been added to this gift registry.';

    /**
     * Assert that product can be added to active gift registry from Wishlist
     *
     * @param CatalogProductView $catalogProductView
     * @param CatalogProductSimple $product
     * @param GiftRegistry $giftRegistry
     * @param WishlistIndex $wishlistIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryItems $giftRegistryItems
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CatalogProductSimple $product,
        GiftRegistry $giftRegistry,
        WishlistIndex $wishlistIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryItems $giftRegistryItems,
        BrowserInterface $browser
    ) {
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->clickAddToWishlist();
        $wishlistIndex->getGiftRegistryWishlistBlock()->addToGiftRegistry($giftRegistry->getTitle());
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $wishlistIndex->getMessagesBlock()->getSuccessMessage(),
            'Wrong success message is displayed.'
        );
        $giftRegistryIndex->open()->getGiftRegistryGrid()->eventAction($giftRegistry->getTitle(), 'Manage Items');
        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryItems->getGiftRegistryItemsBlock()->isProductInGrid($product),
            'Product can not be added to active gift registry \'' . $giftRegistry->getTitle() . '\' from Wishlist.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product can be added to active gift registry from Wishlist.';
    }
}
