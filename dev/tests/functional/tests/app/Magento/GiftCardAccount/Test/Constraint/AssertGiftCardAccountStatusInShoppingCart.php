<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Constraint;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Customer\Test\Fixture\Customer;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\Mtf\Client\BrowserInterface;

/**
 * Assert that created gift card account can be verified on the frontend in Shopping Cart.
 */
class AssertGiftCardAccountStatusInShoppingCart extends AbstractAssertGiftCardAccountOnFrontend
{
    /**
     * Assert that created gift card account can be verified on the frontend in Shopping Cart.
     *
     * @param CheckoutCart $checkoutCart
     * @param GiftCardAccount $giftCardAccount
     * @param Customer $customer
     * @param CatalogProductSimple $product
     * @param CatalogProductView $catalogProductView
     * @param BrowserInterface $browser
     * @param string $code
     * @return void
     */
    public function processAssert(
        CheckoutCart $checkoutCart,
        GiftCardAccount $giftCardAccount,
        Customer $customer,
        CatalogProductSimple $product,
        CatalogProductView $catalogProductView,
        BrowserInterface $browser,
        $code
    ) {
        $this->login($customer);
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $catalogProductView->getViewBlock()->clickAddToCart();
        $catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $data = $giftCardAccount->getData();
        $data['code'] = $code;
        $checkoutCart->open();
        $checkoutCart->getGiftCardAccountBlock()->checkStatusAndBalance($code);
        $result = $this->prepareData($data, $checkoutCart);
        \PHPUnit_Framework_Assert::assertEquals(
            $result['fixtureData'],
            $result['pageData'],
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift card account data is correct on the frontend in Shopping Cart.';
    }
}
