<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Handler\Ui;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Ui;

/**
 * Class CreateOrder
 * Create an order based on the fixture provided.
 *
 */
class CreateOrder extends Ui
{
    /**
     * Create order
     *
     * @param FixtureInterface $fixture [optional]
     * @return mixed|string
     */
    public function persist(FixtureInterface $fixture = null)
    {
        //Ensure shopping cart is empty
        $checkoutCartPage = Factory::getPageFactory()->getCheckoutCartIndex();
        $checkoutCartPage->open();
        $checkoutCartPage->getCartBlock()->clearShoppingCart();

        $products = $fixture->getProducts();

        foreach ($products as $product) {
            $productPage = Factory::getPageFactory()->getCatalogProductView();
            Factory::getClientBrowser()->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
            $productPage->getViewBlock()->addToCart($product);
            $productPage->getMessagesBlock()->waitSuccessMessage();
        }

        //Proceed to checkout
        $checkoutCartPage = Factory::getPageFactory()->getCheckoutCartIndex();
        $checkoutCartPage->open();
        $checkoutCartPage->getCartBlock()->getOnepageLinkBlock()->proceedToCheckout();

        //Complete checkout
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

        $checkoutOnePageSuccess = Factory::getPageFactory()->getCheckoutOnepageSuccess();
        return $checkoutOnePageSuccess->getSuccessBlock()->isVisible()
            ? $checkoutOnePageSuccess->getSuccessBlock()->getOrderId($fixture)
            : false;
    }
}
