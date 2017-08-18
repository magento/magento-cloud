<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountLogout;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that Gift Wrapping can be found in checkout cart on frontend.
 */
class AssertGiftWrappingOnFrontendCheckoutCart extends AbstractConstraint
{
    /**
     * Assert that Gift Wrapping can be found during one page checkout on frontend.
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param BrowserInterface $browser
     * @param array $giftWrapping
     * @param CatalogProductSimple $product
     * @param Customer $customer
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        BrowserInterface $browser,
        array $giftWrapping,
        CatalogProductSimple $product,
        Customer $customer,
        CustomerAccountLogout $customerAccountLogout
    ) {
        // Preconditions
        $customer->persist();
        $product->persist();
        // Steps
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->clickAddToCartButton();
        $catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $checkoutCart->open();
        $giftWrappingsOrderAvailable = $checkoutCart->getGiftOptionsOrderBlock()->getGiftWrappingsAvailable();
        $giftWrappingsItemsAvailable = $checkoutCart->getGiftOptionsItemBlock()->getGiftWrappingsAvailable();
        $matches = [];
        foreach ($giftWrapping as $item) {
            if (in_array($item->getDesign(), $giftWrappingsOrderAvailable + $giftWrappingsItemsAvailable)) {
                $matches[] = $item->getDesign();
            }
        }
        $customerAccountLogout->open();
        \PHPUnit_Framework_Assert::assertNotEmpty(
            $matches,
            'Gift Wrapping is not present in shopping cart on frontend.'
            . "\nLog:\n" . implode(";\n", $matches)
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Wrapping can be found during one page checkout on frontend.';
    }
}
