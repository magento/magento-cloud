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
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryEdit;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\TestCase\Injectable;

/**
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
 * 4. Press on appropriate Gift Registry "Edit" button
 * 5. Edit data according to DataSet
 * 6. Edit data according to DataSet
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-26962
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateGiftRegistryFrontendEntityTest extends Injectable
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
     * Gift Registry edit type page
     *
     * @var GiftRegistryEdit
     */
    protected $giftRegistryEdit;

    /**
     * Injection data
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryEdit $giftRegistryEdit
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryEdit $giftRegistryEdit
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->giftRegistryEdit = $giftRegistryEdit;
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
     * Update Gift Registry entity test
     *
     * @param GiftRegistry $giftRegistryOrigin
     * @param GiftRegistry $giftRegistry
     * @param Customer $customer
     * @return void
     */
    public function test(GiftRegistry $giftRegistryOrigin, GiftRegistry $giftRegistry, Customer $customer)
    {
        // Preconditions
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $giftRegistryOrigin->persist();

        // Steps
        $this->cmsIndex->open();
        $this->cmsIndex->getLinksBlock()->openLink("My Account");
        $this->customerAccountIndex->getAccountMenuBlock()->openMenuItem('Gift Registry');
        $this->giftRegistryIndex->getGiftRegistryGrid()->eventAction($giftRegistryOrigin->getTitle(), 'Edit');
        $this->giftRegistryEdit->getCustomerEditForm()->fill($giftRegistry);
        $this->giftRegistryEdit->getActionsToolbarBlock()->save();
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
