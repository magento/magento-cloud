<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Catalog\Test\Page\Product\CatalogProductView;
use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\GiftRegistry\Test\Page\GiftRegistryItems;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for Manage/UpdateItemsGiftRegistryEntity on the Frontend
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Customer is created
 * 2. Gift Registry is created
 * 3. Product is created and added to gift registry
 *
 * Steps:
 * 1. Go to frontend
 * 2. Go to My Account -> Gift Registry
 * 3. Press on appropriate Gift Registry "Manage Items" button
 * 4. Edit data according to DataSet
 * 5. Click Update button
 * 6. Perform Asserts
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-28196
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateGiftRegistryItemsFrontendEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * Cms index page
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Customer account index page
     *
     * @var CustomerAccountIndex
     */
    protected $customerAccountIndex;

    /**
     * Gift Registry index page
     *
     * @var GiftRegistryIndex
     */
    protected $giftRegistryIndex;

    /**
     * Browser interface
     *
     * @var BrowserInterface
     */
    protected $browser;

    /**
     * Product view page
     *
     * @var CatalogProductView
     */
    protected $catalogProductView;

    /**
     * Checkout cart page
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * GiftRegistry items page
     *
     * @var GiftRegistryItems
     */
    protected $giftRegistryItems;

    /**
     * Injection data
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param BrowserInterface $browser
     * @param CatalogProductView $catalogProductView
     * @param CheckoutCart $checkoutCart
     * @param GiftRegistryItems $giftRegistryItems
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        BrowserInterface $browser,
        CatalogProductView $catalogProductView,
        CheckoutCart $checkoutCart,
        GiftRegistryItems $giftRegistryItems
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->browser = $browser;
        $this->catalogProductView = $catalogProductView;
        $this->checkoutCart = $checkoutCart;
        $this->giftRegistryItems = $giftRegistryItems;
    }

    /**
     * Create customer and product
     *
     * @param CatalogProductSimple $product
     * @param Customer $customer
     * @return array
     */
    public function __prepare(
        CatalogProductSimple $product,
        Customer $customer
    ) {
        $product->persist();
        $customer->persist();

        return [
            'customer' => $customer,
            'product' => $product
        ];
    }

    /**
     * Update GiftRegistry Items Entity on the Frontend
     *
     * @param GiftRegistry $giftRegistry
     * @param Customer $customer
     * @param CatalogProductSimple $product
     * @param array $updateOptions
     * @return void
     */
    public function test(
        GiftRegistry $giftRegistry,
        Customer $customer,
        CatalogProductSimple $product,
        array $updateOptions
    ) {
        // Preconditions
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $giftRegistry->persist();
        $this->browser->open($_ENV['app_frontend_url'] . $product->getUrlKey() . '.html');
        $this->catalogProductView->getViewBlock()->clickAddToCart();
        $this->catalogProductView->getMessagesBlock()->waitSuccessMessage();
        $this->checkoutCart->open();
        $this->checkoutCart->getGiftRegistryCart()->addToGiftRegistry($giftRegistry->getTitle());

        // Steps
        $this->cmsIndex->open();
        $this->cmsIndex->getLinksBlock()->openLink("My Account");
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem("Gift Registry");
        $this->giftRegistryIndex->getGiftRegistryGrid()->eventAction($giftRegistry->getTitle(), 'Manage Items');
        $this->giftRegistryItems->getGiftRegistryItemsBlock()->fillItemForm($product, $updateOptions);
        $this->giftRegistryItems->getGiftRegistryItemsBlock()->updateGiftRegistry();
    }

    /**
     * Log out after test
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager->create(\Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep::class)->run();
    }
}
