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
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\ObjectManager;

/**
 * Assert that deleted Gift Wrapping can not be found in checkout cart on frontend.
 */
class AssertGiftWrappingNotOnFrontendCheckoutCart extends AbstractConstraint
{
    /**
     * Assert that deleted Gift Wrapping can not be found during one page checkout on frontend.
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param BrowserInterface $browser
     * @param GiftWrapping|GiftWrapping[] $giftWrapping
     * @param CatalogProductSimple $product
     * @param Customer $customer
     */
    public function processAssert(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        BrowserInterface $browser,
        $giftWrapping,
        CatalogProductSimple $product,
        Customer $customer
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
        $giftWrappings = !is_array($giftWrapping) ? [$giftWrapping] : $giftWrapping;
        foreach ($giftWrappings as $giftWrapping) {
            if (in_array($giftWrapping->getDesign(), $giftWrappingsOrderAvailable + $giftWrappingsItemsAvailable)) {
                $matches[] = $giftWrapping->getDesign();
            }
        }
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();

        \PHPUnit_Framework_Assert::assertEmpty(
            $matches,
            'Gift Wrapping is present in shopping cart on frontend.'
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
        return 'Gift Wrapping can not be found during one page checkout on frontend.';
    }
}
