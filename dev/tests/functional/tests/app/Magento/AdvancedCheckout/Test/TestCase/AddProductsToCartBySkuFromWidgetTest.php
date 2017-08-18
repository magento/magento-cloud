<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdvancedCheckout\Test\TestCase;

use Magento\AdvancedCheckout\Test\Fixture\AdvancedCheckoutWidget;
use Magento\AdvancedCheckout\Test\Page\CustomerOrderSku;
use Magento\PageCache\Test\Page\Adminhtml\AdminCache;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Widget\Test\Page\Adminhtml\WidgetInstanceEdit;
use Magento\Widget\Test\Page\Adminhtml\WidgetInstanceIndex;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Preconditions:
 * 1. Register Customer.
 * 2. Create Product.
 * 3. Create widget "Order by Sku".
 *
 * Steps:
 * 1. Login to Frontend.
 * 2. Navigate to My Account.
 * 3. Fill data in widget according to dataset.
 * 4. Click Add to Cart button.
 * 5. Perform all asserts.
 *
 * @group Add_by_SKU
 * @ZephyrId MAGETWO-29781
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AddProductsToCartBySkuFromWidgetTest extends AbstractAdvancedCheckoutEntityTest
{
    /* tags */
    const MVP = 'no';
    const TEST_TYPE = 'extended_acceptance_test';
    const SEVERITY = 'S1';
    /* end tags */

    /**
     * Widget instance page.
     *
     * @var WidgetInstanceIndex
     */
    protected $widgetInstanceIndex;

    /**
     * Widget instance edit page.
     *
     * @var WidgetInstanceEdit
     */
    protected $widgetInstanceEdit;

    /**
     * Order by SKU widget.
     *
     * @var AdvancedCheckoutWidget
     */
    protected $widget;

    /**
     * Fixture Factory.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * Page AdminCache.
     *
     * @var AdminCache
     */
    protected $adminCache;

    /**
     * Injection data.
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param CustomerOrderSku $customerOrderSku
     * @param CheckoutCart $checkoutCart
     * @param WidgetInstanceIndex $widgetInstanceIndex
     * @param WidgetInstanceEdit $widgetInstanceEdit
     * @param FixtureFactory $fixtureFactory
     * @param AdminCache $adminCache
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        CustomerOrderSku $customerOrderSku,
        CheckoutCart $checkoutCart,
        WidgetInstanceIndex $widgetInstanceIndex,
        WidgetInstanceEdit $widgetInstanceEdit,
        FixtureFactory $fixtureFactory,
        AdminCache $adminCache
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->customerOrderSku = $customerOrderSku;
        $this->checkoutCart = $checkoutCart;
        $this->widgetInstanceIndex = $widgetInstanceIndex;
        $this->widgetInstanceEdit = $widgetInstanceEdit;
        $this->fixtureFactory = $fixtureFactory;
        $this->adminCache = $adminCache;
    }

    /**
     * Create customer and widget.
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
     * Add product to cart by sku from widget.
     *
     * @param Customer $customer
     * @param string $products
     * @param array $orderOptions
     * @param string $cartBlock
     * @return array
     */
    public function test(Customer $customer, $products, array $orderOptions, $cartBlock)
    {
        // Preconditions
        $products = $this->createProducts($products);
        $orderOptions = $this->prepareOrderOptions($products, $orderOptions);
        $this->widget = $this->fixtureFactory->create(
            \Magento\AdvancedCheckout\Test\Fixture\AdvancedCheckoutWidget::class,
            ['dataset' => 'order_by_sku']
        );
        $this->widget->persist();
        $this->adminCache->open();
        $this->adminCache->getActionsBlock()->flushMagentoCache();
        $this->adminCache->getMessagesBlock()->waitSuccessMessage();
        // Steps
        $this->cmsIndex->open();
        $this->loginCustomer($customer);
        $this->cmsIndex->getLinksBlock()->openLink("My Account");
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem("Order by SKU");
        $this->customerAccountIndex->getOrderBySkuBlock()->fillForm($orderOptions);
        $this->customerAccountIndex->getOrderBySkuBlock()->addToCart();

        $filteredProducts = $this->filterProducts($products, $cartBlock);

        return [
            'products' => isset($filteredProducts['cart']) ? $filteredProducts['cart'] : [],
            'requiredAttentionProducts' => isset($filteredProducts['required_attention'])
                ? $filteredProducts['required_attention']
                : []
        ];
    }

    /**
     * Clear shopping cart and delete widget.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->checkoutCart->open()->getCartBlock()->clearShoppingCart();
        $this->widgetInstanceIndex->open();
        $this->widgetInstanceIndex->getWidgetGrid()->searchAndOpen(['title' => $this->widget->getTitle()]);
        $this->widgetInstanceEdit->getPageActionsBlock()->delete();
        $this->widgetInstanceEdit->getModalBlock()->acceptAlert();
    }
}
