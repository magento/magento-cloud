<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for Delete frontend GiftRegistryEntity
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Register Customer
 * 2. Gift Registry is created
 *
 * Steps:
 * 1. Go to frontend
 * 2. Login as a Customer
 * 3. Go to My Account -> Gift Registry
 * 4. Press on appropriate Gift Registry "Delete" button
 * 5. Perform Asserts
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-26648
 */
class DeleteGiftRegistryFrontendEntityTest extends Injectable
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
     * Step LogoutCustomerOnFrontendStep
     *
     * @var LogoutCustomerOnFrontendStep
     */
    protected $customerAccountLogout;

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
     * Injection data
     *
     * @param CmsIndex $cmsIndex
     * @param LogoutCustomerOnFrontendStep $customerAccountLogout
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        LogoutCustomerOnFrontendStep $customerAccountLogout,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountLogout = $customerAccountLogout;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->giftRegistryIndex = $giftRegistryIndex;
    }

    /**
     * Create customer and product
     *
     * @param CatalogProductSimple $product
     * @param Customer $customer
     * @return array
     */
    public function __prepare(CatalogProductSimple $product, Customer $customer)
    {
        $product->persist();
        $customer->persist();

        return [
            'customer' => $customer,
            'product' => $product
        ];
    }

    /**
     * Create Gift Registry entity test
     *
     * @param Customer $customer
     * @param GiftRegistry $giftRegistry
     * @return void
     */
    public function test(Customer $customer, GiftRegistry $giftRegistry)
    {
        // Preconditions
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $giftRegistry->persist();

        // Steps
        $this->cmsIndex->open();
        $this->cmsIndex->getLinksBlock()->openLink("My Account");
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem('Gift Registry');
        $this->giftRegistryIndex->getGiftRegistryGrid()->eventAction($giftRegistry->getTitle(), 'Delete', true);
    }

    /**
     * Log out after test
     *
     * @return void
     */
    public function tearDown()
    {
        $this->customerAccountLogout->run();
    }
}
