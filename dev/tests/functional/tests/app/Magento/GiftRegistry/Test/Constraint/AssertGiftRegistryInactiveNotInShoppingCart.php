<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryInactiveNotInShoppingCart
 * Assert that product can't be added to Inactive GiftRegistry from Shopping Cart
 */
class AssertGiftRegistryInactiveNotInShoppingCart extends AbstractConstraint
{
    /**
     * Assert that product can not be added to inactive gift registry from Shopping Cart
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param CatalogProductSimple $product
     * @param GiftRegistry $giftRegistry
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        CatalogProductSimple $product,
        GiftRegistry $giftRegistry,
        BrowserInterface $browser
    ) {
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->clickAddToCart();
        \PHPUnit_Framework_Assert::assertFalse(
            $checkoutCart->getGiftRegistryCart()->isGiftRegistryAvailable($giftRegistry),
            'Product can be added to inactive gift registry \'' . $giftRegistry->getTitle() . '\' from Shopping Cart.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Product can not be added to inactive gift registry from Shopping Cart.';
    }
}
