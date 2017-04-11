<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Test\TestCase\Guest;

use Magento\Checkout\Test\Fixture\Checkout;
use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Paypal\Test\Block\Form\PayflowAdvanced\CcAdvanced;
use Magento\Paypal\Test\Block\Form\PayflowAdvanced\CcLink;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\TestCase\Functional;

/**
 * Class PaypalCreditCardTest
 *
 * Test one page checkout with PayPal credit card payments (payments advanced and payflow link).
 *
 */
class PaypalCreditCardTest extends Functional
{
    /* tags */
    const TEST_TYPE = '3rd_party_test_deprecated';
    /* end tags */

    /**
     * Guest checkout using PayPal payment method specified by the dataprovider.
     *
     * @param Checkout $fixture
     * @param string $formBlockFunction
     * @dataProvider dataProviderCheckout
     *
     * @ZephyrId MAGETWO-12991, MAGETWO-12974
     */
    public function testOnepageCheckout(Checkout $fixture, $formBlockFunction)
    {
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
        $shippingMethod = $fixture->getShippingMethods()->getData('fields');
        $checkoutOnePage->getShippingMethodBlock()->selectShippingMethod($shippingMethod);
        $checkoutOnePage->getShippingMethodBlock()->clickContinue();
        $payment = [
            'method' => $fixture->getPaymentMethod()->getPaymentCode(),
            'dataConfig' => $fixture->getPaymentMethod()->getDataConfig()
        ];
        $checkoutOnePage->getPaymentMethodsBlock()->selectPaymentMethod($payment);
        $checkoutOnePage->getPaymentMethodsBlock()->clickContinue();
        $checkoutOnePage->getReviewBlock()->placeOrder();

        /** @var \Magento\Paypal\Test\Block\Form\PayflowAdvanced\CcAdvanced $formBlock */
        $formBlock = call_user_func_array([$this, $formBlockFunction], [$checkoutOnePage]);
        $formBlock->fill($fixture->getCreditCard());
        $formBlock->pressContinue();
        Factory::getClientBrowser()->selectWindow();

        //Verify order in Backend
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

        if ($fixture->getCommentHistory()) {
            $expectedAuthorizedAmount = $fixture->getCommentHistory();
        } else {
            $expectedAuthorizedAmount = 'Authorized amount of $' . $fixture->getGrandTotal();
        }
        $this->assertContains(
            $expectedAuthorizedAmount,
            Factory::getPageFactory()->getSalesOrderView()->getOrderHistoryBlock()->getCommentsHistory(),
            'Incorrect authorized amount value for the order #' . $orderId
        );
    }

    /**
     * Data providers for checking out
     *
     * @return array
     */
    public function dataProviderCheckout()
    {
        return [
            [Factory::getFixtureFactory()->getMagentoCheckoutGuestPaypalAdvanced(), 'getPayflowAdvancedCcBlock'],
            [Factory::getFixtureFactory()->getMagentoCheckoutGuestPaypalPayflowLink(), 'getPayflowLinkCcBlock'],
        ];
    }

    /**
     * Return the block associated with the PayPal Payments Advanced credit card form.
     *
     * @param CheckoutOnepage $checkoutOnePage
     * @return CcAdvanced
     */
    public function getPayflowAdvancedCcBlock(CheckoutOnepage $checkoutOnePage)
    {
        return $checkoutOnePage->getPayflowAdvancedCcBlock();
    }

    /**
     * Return the block associated with the PayPal Payflow Link credit card form.
     *
     * @param CheckoutOnepage $checkoutOnePage
     * @return CcLink
     */
    public function getPayflowLinkCcBlock(CheckoutOnepage $checkoutOnePage)
    {
        return $checkoutOnePage->getPayflowLinkCcBlock();
    }
}
