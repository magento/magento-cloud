<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Handler\Ui;

use Magento\Mtf\Factory\Factory;
use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Handler\Ui;

/**
 * Class CreatePaypalExpressOrder
 * Create a product
 *
 */
class CreatePaypalExpressOrder extends Ui
{
    /**
     * Create product
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

        $checkoutCartPage = Factory::getPageFactory()->getCheckoutCartIndex();
        $checkoutCartPage->open();
        $checkoutCartPage->getCartBlock()->paypalCheckout();

        $paypalCustomer = $fixture->getPaypalCustomer();
        $paypalPage = Factory::getPageFactory()->getPaypal();
        $paypalPage->getLoginExpressBlock()->login($paypalCustomer);
        $paypalPage->getReviewExpressBlock()->continueCheckout();

        $shippingMethod = $fixture->getShippingMethods()->getData('fields');
        $checkoutReviewPage = Factory::getPageFactory()->getPaypalExpressReview();
        $checkoutReviewPage->getReviewBlock()->selectShippingMethod($shippingMethod);
        $checkoutReviewPage->getReviewBlock()->placeOrder();

        $orderId = Factory::getPageFactory()->getCheckoutOnepageSuccess()->getSuccessBlock()->getOrderId($fixture);

        return $orderId;
    }
}
