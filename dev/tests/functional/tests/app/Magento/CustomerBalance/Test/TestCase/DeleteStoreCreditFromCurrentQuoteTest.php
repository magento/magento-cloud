<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Customer\Test\Fixture\Address;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Enable free shipping in configuration.
 * 2. Create simple product.
 * 3. Create customer.
 * 4. Add 100 Store Credit on customer balance.
 *
 * Steps:
 * 1. Login to frontend as customer
 * 2. Add simple product to shopping cart.
 * 3. Click "Proceed Checkout" button.
 * 4. Fill in shipping address.
 * 5. Select shipping method and click "Continue" button.
 * 6. On Payment Information step press button to apply store credits.
 * 7. Select payment method.
 * 8. Open shopping cart.
 * 9. Click "Remove" link for Store Credit.
 * 8. Perform all asserts.
 *
 * @group Customers
 * @ZephyrId MAGETWO-27688
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DeleteStoreCreditFromCurrentQuoteTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    /* end tags */

    /**
     * CatalogProductView Page.
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * CheckoutCart Page.
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * CheckoutOnepage Page.
     *
     * @var CheckoutOnepage
     */
    protected $checkoutOnepage;

    /**
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Enable free shipping in configuration and create simple product.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $config = $fixtureFactory->createByCode('configData', ['dataset' => 'freeshipping']);
        $config->persist();

        $product = $fixtureFactory->createByCode('catalogProductSimple');
        $product->persist();

        return ['product' => $product];
    }

    /**
     * Injection data.
     *
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param CheckoutOnepage $checkoutOnepage
     * @param FixtureFactory $fixtureFactory
     * @return void
     */
    public function __inject(
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        CheckoutOnepage $checkoutOnepage,
        FixtureFactory $fixtureFactory
    ) {
        $this->catalogProductView = $catalogProductView;
        $this->checkoutCart = $checkoutCart;
        $this->checkoutOnepage = $checkoutOnepage;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Delete Store Credit from current quote.
     *
     * @param Customer $customer
     * @param BrowserInterface $browser
     * @param CatalogProductSimple $product
     * @param Address $shippingAddress
     * @param array $shipping
     * @param array $payment
     * @return void
     */
    public function test(
        Customer $customer,
        BrowserInterface $browser,
        CatalogProductSimple $product,
        Address $shippingAddress,
        array $shipping,
        array $payment
    ) {
        // Preconditions
        $customer->persist();
        $customerBalance = $this->fixtureFactory->createByCode(
            'customerBalance',
            [
                'dataset' => 'customerBalance_100',
                'data' => [
                    'customer_id' => ['customer' => $customer],
                ]
            ]
        );
        $customerBalance->persist();

        // Steps
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->clickAddToCartButton();
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $this->checkoutCart->open();
        $this->checkoutCart->getCartBlock()->getOnepageLinkBlock()->proceedToCheckout();
        $this->checkoutOnepage->getShippingBlock()->fill($shippingAddress);
        $this->checkoutOnepage->getShippingMethodBlock()->selectShippingMethod($shipping);
        $this->checkoutOnepage->getShippingMethodBlock()->clickContinue();
        $this->checkoutOnepage->getStoreCreditBlock()->useStoreCredit();
        $this->checkoutOnepage->getPaymentBlock()->selectPaymentMethod($payment);
        $this->checkoutCart->open();
        $this->checkoutCart->getCustomerBalanceTotalsBlock()->waitForUpdatedTotals();
        $this->checkoutCart->getCustomerBalanceTotalsBlock()->removeStoreCredit();
    }
}
