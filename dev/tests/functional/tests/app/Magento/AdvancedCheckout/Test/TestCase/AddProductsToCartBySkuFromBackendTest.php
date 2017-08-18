<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;

/**
 * Preconditions:
 * 1. Register Customer.
 * 2. Create Product.
 *
 * Steps:
 * 1. Login to Admin.
 * 2. Navigate to Sales > Operations > Order.
 * 3. Click 'Create New Order' button.
 * 4. Select created customer or create new one.
 * 5. Click 'Add Products By SKU' button.
 * 6. Enter product SKU.
 * 7. Click 'Add to Order' button.
 * 8. Perform assertions.
 *
 * @group Add_by_SKU
 * @ZephyrId MAGETWO-17411
 */
class AddProductsToCartBySkuFromBackendTest extends AbstractAdvancedCheckoutEntityTest
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Sales order index page.
     *
     * @var OrderIndex
     */
    protected $orderIndex;

    /**
     * Sales order create index page.
     *
     * @var OrderCreateIndex
     */
    protected $orderCreateIndex;

    /**
     * Injection data.
     *
     * @param OrderIndex $orderIndex
     * @param OrderCreateIndex $orderCreateIndex
     * @return void
     */
    public function __inject(
        OrderIndex $orderIndex,
        OrderCreateIndex $orderCreateIndex
    ) {
        $this->orderIndex = $orderIndex;
        $this->orderCreateIndex = $orderCreateIndex;
    }

    /**
     * Adding to cart on backend.
     *
     * @param Customer $customer
     * @param array $orderOptions
     * @param array $products
     * @param string|null $config
     * @return array
     */
    public function test(
        Customer $customer,
        array $orderOptions,
        array $products = [],
        $config = null
    ) {
        // Preconditions
        $this->configuration = ($config) ? $config : '-';
        $this->setupConfiguration();
        $customer->persist();
        $products = $this->createProducts($products);

        // Steps
        $this->orderIndex->open();
        $this->orderIndex->getGridPageActions()->addNew();
        $this->orderCreateIndex->getCustomerBlock()->selectCustomer($customer);
        $orderOptions = $this->prepareOrderOptions($products, $orderOptions);
        $createBlock = $this->orderCreateIndex->getCreateBlock();
        $createBlock->getItemsBlock()->clickAddProductsBySku();
        $createBlock->getOrderAdditionalBlock()->fillForm($orderOptions);
        $createBlock->getItemsBlock()->clickAddToOrder();
        return [
            'products' => $products,
            'orderOptions' => $orderOptions,
        ];
    }
}
