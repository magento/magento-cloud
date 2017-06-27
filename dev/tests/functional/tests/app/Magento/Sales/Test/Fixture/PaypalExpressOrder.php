<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Test\Fixture;

use Magento\Mtf\Factory\Factory;

/**
 * Class PaypalExpress
 * PayPal Express Method
 * Guest checkout using "Checkout with PayPal" button from product page and Free Shipping
 *
 * @ZephyrId MAGETWO-12415
 */
class PaypalExpressOrder extends OrderCheckout
{
    /**
     * Prepare data for guest checkout using "Checkout with PayPal" button on product page
     */
    protected function _initData()
    {
        $this->checkoutFixture = Factory::getFixtureFactory()->getMagentoCheckoutPaypalExpress();
        //Verification data
        $this->_data = [
            'totals' => [
                'grand_total' => '10.83',
            ],
        ];
    }

    /**
     * Persists prepared data into application
     */
    public function persist()
    {
        $this->checkoutFixture->persist();
        if ($this->additionalProducts !== null) {
            foreach ($this->additionalProducts as $product) {
                $this->checkoutFixture->addProduct($product);
            }
        }
        $this->orderId = Factory::getApp()->magentoCheckoutCreatePaypalExpressOrder($this->checkoutFixture);
    }

    /**
     * @return \Magento\Customer\Test\Fixture\Address
     */
    public function getBillingAddress()
    {
        return $this->checkoutFixture->getBillingAddress();
    }

    /**
     * @return \Magento\Customer\Test\Fixture\Customer
     */
    public function getCustomer()
    {
        return $this->checkoutFixture->getPayPalCustomer();
    }

    /**
     * @param int $index
     * @return \Magento\Catalog\Test\Fixture\CatalogProductSimple
     */
    public function getProduct($index)
    {
        return $this->checkoutFixture->products[$index];
    }

    /**
     * @returns array
     */
    public function getProducts()
    {
        return $this->checkoutFixture->getProducts();
    }

    /**
     * @param array
     */
    public function setAdditionalProducts($products = null)
    {
        $this->additionalProducts = $products;
    }
}
