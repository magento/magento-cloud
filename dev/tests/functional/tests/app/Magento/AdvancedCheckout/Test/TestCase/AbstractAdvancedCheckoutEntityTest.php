<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\TestCase;

use Magento\AdvancedCheckout\Test\Page\CustomerOrderSku;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Abstract class for AdvancedCheckoutEntity tests.
 */
abstract class AbstractAdvancedCheckoutEntityTest extends Injectable
{
    /**
     * Cms index page.
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Customer account index page.
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Customer order by SKU page.
     *
     * @var CustomerOrderSku
     */
    protected $customerOrderSku;

    /**
     * Checkout cart page.
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * Configuration data set name.
     *
     * @var string
     */
    protected $configuration;

    /**
     * Filter products.
     *
     * @param array $products
     * @param string $cartBlock
     * @return array
     */
    protected function filterProducts(array $products, $cartBlock)
    {
        $filteredProducts = [];
        $cartBlock = explode(',', $cartBlock);
        foreach ($cartBlock as $key => $value) {
            $filteredProducts[trim($value)][$key] = $products[$key];
        }

        return $filteredProducts;
    }

    /**
     * Create products.
     *
     * @param array $products
     * @return array
     */
    protected function createProducts(array $products)
    {
        if (empty($products)) {
            return [null];
        }
        $createProductsStep = $this->objectManager->create(
            \Magento\Catalog\Test\TestStep\CreateProductsStep::class,
            ['products' => $products]
        );

        return $createProductsStep->run()['products'];
    }

    /**
     * Prepare order options.
     *
     * @param array $products
     * @param array $orderOptions
     * @return array
     */
    protected function prepareOrderOptions(array $products, array $orderOptions)
    {
        foreach ($orderOptions as $key => $value) {
            $value = is_array($value) ? $value : explode(',', $value);
            foreach ($value as $item => $option) {
                $orderOptions[$item][$key] = trim($option);
            }
            unset($orderOptions[$key]);
        }

        return $this->addProductSkuToOrderOptions($products, $orderOptions);
    }

    /**
     * Add product SKUs to Order options data.
     *
     * @param array $products
     * @param array $orderOptions
     * @return array
     */
    protected function addProductSkuToOrderOptions(array $products, array $orderOptions)
    {
        foreach ($products as $key => $product) {
            $productSku = $product === null
                ? $productSku = (isset($orderOptions[$key]['sku']) ? $orderOptions[$key]['sku'] : '-')
                : $productSku = $product->getSku();
            $orderOptions[$key]['sku'] = (isset($orderOptions[$key]['sku'])
                && ($orderOptions[$key]['sku'] === 'simpleWithOptionCompoundSku'))
                ? $productSku . '-' . $product->getCustomOptions()[0]['options'][0]['sku']
                : $productSku;
        }

        return $orderOptions;
    }

    /**
     * Setup configuration.
     *
     * @param bool $rollback
     * @return void
     */
    protected function setupConfiguration($rollback = false)
    {
        $setConfigStep = $this->objectManager->create(
            \Magento\Config\Test\TestStep\SetupConfigurationStep::class,
            ['configData' => $this->configuration, 'rollback' => $rollback]
        );
        $setConfigStep->run();
    }

    /**
     * Login customer.
     *
     * @param Customer $customer
     * @return void
     */
    protected function loginCustomer(Customer $customer)
    {
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
    }
}
