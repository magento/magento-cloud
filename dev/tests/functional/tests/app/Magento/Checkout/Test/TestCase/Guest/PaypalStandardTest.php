<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\TestCase\Guest;

use Magento\Checkout\Test\Fixture\Checkout;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\TestCase\Functional;

/**
 * Class OnepageTest
 * Test one page with PayPal Standard payment method
 *
 */
class PaypalStandardTest extends Functional
{
    /* tags */
    const TEST_TYPE = '3rd_party_test_deprecated';
    /* end tags */

    /**
     * Guest checkout using PayPal Payments Standard method and offline shipping method
     *
     * @ZephyrId MAGETWO-12964
     */
    public function testOnepageCheckout()
    {
        /** @var Checkout $fixture */
        $fixture = Factory::getFixtureFactory()->getMagentoCheckoutGuestPayPalStandard();
        $fixture->persist();

        //Ensure shopping cart is empty
        $checkoutCartPage = Factory::getPageFactory()->getCheckoutCartIndex();
        $checkoutCartPage->open();
        $checkoutCartPage->getCartBlock()->clearShoppingCart();

        //Add products to cart
        $products = $fixture->getProducts();
        foreach ($products as $product) {
            $productPage = Factory::getPageFactory()->getCatalogProductView();
            Factory::getClientBrowser()->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
            $productPage->getViewBlock()->addToCart($product);
            Factory::getPageFactory()->getCheckoutCartIndex()->getMessagesBlock()->waitSuccessMessage();
        }

        //Proceed to checkout
        $checkoutCartPage = Factory::getPageFactory()->getCheckoutCartIndex();
        $checkoutCartPage->open();
        $checkoutCartPage->getCartBlock()->getOnepageLinkBlock()->proceedToCheckout();

        //Proceed Checkout
        /** @var \Magento\Checkout\Test\Page\CheckoutOnepage $checkoutOnePage */
        $checkoutOnePage = Factory::getPageFactory()->getCheckoutOnepage();
        $checkoutOnePage->getLoginBlock()->checkoutMethod($fixture);
        $billingAddress = $fixture->getBillingAddress();
        $checkoutOnePage->getBillingBlock()->fillBilling($billingAddress);
        $checkoutOnePage->getBillingBlock()->clickContinue();
        if ($fixture instanceof \Magento\Shipping\Test\Fixture\Method) {
            $shippingMethod = $fixture->getData('fields');
        } else {
            $shippingMethod = $fixture->getShippingMethods()->getData('fields');
        }
        $checkoutOnePage->getShippingMethodBlock()->selectShippingMethod($shippingMethod);
        $checkoutOnePage->getShippingMethodBlock()->clickContinue();
        $payment = [
            'method' => $fixture->getPaymentMethod()->getPaymentCode(),
            'dataConfig' => $fixture->getPaymentMethod()->getDataConfig()
        ];
        $checkoutOnePage->getPaymentMethodsBlock()->selectPaymentMethod($payment, $fixture->getCreditCard());
        $checkoutOnePage->getPaymentMethodsBlock()->clickContinue();
        $checkoutOnePage->getReviewBlock()->placeOrder();

        $paypalCustomer = $fixture->getPaypalCustomer();
        $paypalPage = Factory::getPageFactory()->getPaypal();
        $paypalPage->getBillingBlock()->clickLoginLink();
        $paypalPage->getLoginBlock()->login($paypalCustomer);
        $paypalPage->getReviewBlock()->continueCheckout();
        $paypalPage->getMainPanelBlock()->clickReturnLink();

        //Verify order in Backend
        /** @var \Magento\Checkout\Test\Page\CheckoutOnepageSuccess $successPage */
        $successPage = Factory::getPageFactory()->getCheckoutOnepageSuccess();
        $orderId = $successPage->getSuccessBlock()->getOrderId($fixture);
        $this->_verifyOrder($orderId, $fixture);
    }

    /**
     * Verify order in Backend
     *
     * @param string $orderId
     * @param Checkout $fixture
     */
    protected function _verifyOrder($orderId, Checkout $fixture)
    {
        Factory::getApp()->magentoBackendLoginUser();
        $orderPage = Factory::getPageFactory()->getSalesOrder();
        $orderPage->open();
        $orderPage->getOrderGridBlock()->searchAndOpen(['id' => $orderId]);
        $this->assertContains(
            $fixture->getGrandTotal(),
            Factory::getPageFactory()->getSalesOrderView()->getOrderTotalsBlock()->getGrandTotal(),
            'Incorrect grand total value for the order #' . $orderId
        );

        $expectedAuthorizedAmount = 'captured amount of $' . $fixture->getGrandTotal();
        $this->assertContains(
            $expectedAuthorizedAmount,
            Factory::getPageFactory()->getSalesOrderView()->getOrderHistoryBlock()->getCapturedAmount(),
            'Incorrect captured amount value for the order #' . $orderId
        );
    }
}
