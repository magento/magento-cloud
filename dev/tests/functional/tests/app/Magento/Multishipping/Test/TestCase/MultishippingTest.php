<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Multishipping\Test\TestCase;

use Magento\Multishipping\Test\Fixture\GuestPaypalDirect;
use Magento\Mtf\Factory\Factory;
use Magento\Mtf\TestCase\Functional;

/**
 * Test multiple address page checkout with different configurations.
 */
class MultishippingTest extends Functional
{
    /* tags */
    const TEST_TYPE = '3rd_party_test_deprecated';
    /* end tags */

    /**
     * Place order on frontend via multishipping.
     *
     * @param GuestPaypalDirect $fixture
     * @dataProvider dataProviderMultishippingCheckout
     *
     * @ZephyrId MAGETWO-12836
     */
    public function testMultishippingCheckout(GuestPaypalDirect $fixture)
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
        Factory::getPageFactory()->getCheckoutCartIndex()->open();
        $checkoutCartPage = Factory::getPageFactory()->getMultishippingCheckoutCart();
        $checkoutCartPage->getMultishippingLinkBlock()->multipleAddressesCheckout();

        //Register new customer
        Factory::getPageFactory()->getMultishippingCheckoutLogin()->getLoginBlock()->registerCustomer();
        $multishippingRegisterPage = Factory::getPageFactory()->getMultishippingCheckoutRegister();
        $multishippingRegisterPage->getRegisterBlock()
            ->registerCustomer($fixture->getCustomer(), $fixture->getCustomer()->getDefaultBilling());

        //Mapping products and shipping addresses
        if ($fixture->getNewShippingAddresses()) {
            foreach ($fixture->getNewShippingAddresses() as $address) {
                Factory::getPageFactory()->getMultishippingCheckoutAddresses()->getAddressesBlock()->addNewAddress();
                Factory::getPageFactory()->getMultishippingCheckoutAddressNewShipping()->getEditBlock()
                    ->editCustomerAddress($address);
            }
        }
        $productBindings = $fixture->getBindings();
        Factory::getPageFactory()->getMultishippingCheckoutAddresses()->getAddressesBlock()
            ->selectAddresses($productBindings);

        //Select shipping and payment methods
        $shippingMethods = $fixture->getShippingMethods();
        Factory::getPageFactory()->getMultishippingCheckoutShipping()->getShippingBlock()
            ->selectShippingMethod($shippingMethods);
        $payment = [
            'method' => $fixture->getPaymentMethod()->getPaymentCode(),
            'dataConfig' => $fixture->getPaymentMethod()->getDataConfig(),
            'credit_card' => $fixture->getCreditCard(),
        ];
        Factory::getPageFactory()->getMultishippingCheckoutBilling()->getBillingBlock()->selectPaymentMethod($payment);
        Factory::getPageFactory()->getMultishippingCheckoutOverview()->getOverviewBlock()->placeOrder();

        //Verify order in Backend
        $successPage = Factory::getPageFactory()->getMultishippingCheckoutSuccess();
        $orderIds = $successPage->getSuccessBlock()->getOrderIds(count($fixture->getShippingMethods()));
        $this->_verifyOrder($orderIds, $fixture);
    }

    /**
     * @return array
     */
    public function dataProviderMultishippingCheckout()
    {
        return [
            [Factory::getFixtureFactory()->getMagentoMultishippingGuestPaypalDirect()],
        ];
    }

    /**
     * Verify order in Backend.
     *
     * @param array $orderIds
     * @param GuestPaypalDirect $fixture
     */
    protected function _verifyOrder($orderIds, GuestPaypalDirect $fixture)
    {
        Factory::getApp()->magentoBackendLoginUser();
        $grandTotals = $fixture->getGrandTotal();
        foreach ($orderIds as $num => $orderId) {
            $orderPage = Factory::getPageFactory()->getSalesOrder();
            $orderPage->open();
            $orderPage->getOrderGridBlock()->searchAndOpen(['id' => $orderId]);
            $this->assertEquals(
                $grandTotals[$num],
                Factory::getPageFactory()->getSalesOrderView()->getOrderTotalsBlock()->getGrandTotal(),
                'Incorrect grand total value for the order #' . $orderId
            );
        }
    }
}
