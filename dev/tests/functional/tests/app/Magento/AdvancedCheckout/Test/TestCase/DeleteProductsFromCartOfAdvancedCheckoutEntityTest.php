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

/**
 * Preconditions:
 * 1. Product is created according to dataset.
 * 2. Clear shopping cart.
 * 3. Create Customer.
 * 4. Add to cart product by SKU.
 *
 * Steps:
 * 1. Login to Frontend.
 * 2. Open Shopping Cart.
 * 3. Click Remove button in Product Requiring Section.
 * 4. Perform all asserts.
 *
 * @group Add_by_SKU
 * @ZephyrId MAGETWO-29906
 */
class DeleteProductsFromCartOfAdvancedCheckoutEntityTest extends AbstractAdvancedCheckoutEntityTest
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    const SEVERITY = 'S3';
    /* end tags */

    /**
     * Injection data.
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerOrderSku $customerOrderSku
     * @param CheckoutCart $checkoutCart
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        CustomerOrderSku $customerOrderSku,
        CheckoutCart $checkoutCart
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->customerOrderSku = $customerOrderSku;
        $this->checkoutCart = $checkoutCart;
    }

    /**
     * Create customer.
     *
     * @param Customer $customer
     * @return array
     */
    public function __prepare(Customer $customer)
    {
        $customer->persist();

        return ['customer' => $customer];
    }

    /**
     * Delete products from AdvancedCheckout.
     *
     * @param Customer $customer
     * @param string $orderOptions
     * @param array $products
     * @return array
     */
    public function test(Customer $customer, $orderOptions, array $products = [])
    {
        // Preconditions
        $products = $this->createProducts($products);
        $orderOptions = $this->prepareOrderOptions($products, $orderOptions);

        $this->checkoutCart->open()->getCartBlock()->clearShoppingCart();

        $this->cmsIndex->open();
        $this->loginCustomer($customer);
        $this->cmsIndex->getLinksBlock()->openLink("My Account");
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem("Order by SKU");
        $this->customerOrderSku->getCustomerSkuBlock()->fillForm($orderOptions);
        $this->customerOrderSku->getCustomerSkuBlock()->addToCart();

        // Steps
        $this->checkoutCart->open();
        $this->deleteProducts($products);

        return ['products' => $products];
    }

    /**
     * Delete requiring attention products.
     *
     * @param array $products
     * @return void
     */
    protected function deleteProducts(array $products)
    {
        foreach ($products as $product) {
            $this->checkoutCart->getAdvancedCheckoutCart()->deleteProduct($product);
        }
    }
}
