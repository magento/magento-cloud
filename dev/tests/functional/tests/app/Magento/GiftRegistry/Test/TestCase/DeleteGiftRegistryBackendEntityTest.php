<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\TestCase;

use Magento\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\Adminhtml\GiftRegistryCustomerEdit;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Flow:
 *
 * Preconditions:
 * 1. Create customer
 * 2. Create Gift Registry
 *
 * Steps:
 * 1. Login to backend
 * 2. Go to Customers->All Customers
 * 3. Search and open created customer
 * 4. Navigate to Gift Registry tab
 * 5. Search and open gift registry created in preconditions
 * 6. Click button "Delete Registry"
 * 7. Perform all asserts
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-27034
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DeleteGiftRegistryBackendEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * Customer index page
     *
     * @var CustomerIndex
     */
    protected $customerIndex;

    /**
     * Customer edit page
     *
     * @var CustomerIndexEdit
     */
    protected $customerIndexEdit;

    /**
     * Gift registry edit page
     *
     * @var GiftRegistryCustomerEdit
     */
    protected $giftRegistryCustomerEdit;

    /**
     * Cms index page
     *
     * @var CmsIndex
     */
    protected $cmsIndex;

    /**
     * Step LogoutCustomerOnFrontendStep.
     *
     * @var LogoutCustomerOnFrontendStep
     */
    protected $customerAccountLogout;

    /**
     * Create product
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
            'product' => $product,
            'customer' => $customer,
        ];
    }

    /**
     * Injection data
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerIndex $customerIndex
     * @param CustomerIndexEdit $customerIndexEdit
     * @param GiftRegistryCustomerEdit $giftRegistryCustomerEdit
     * @param LogoutCustomerOnFrontendStep $customerAccountLogout
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerIndex $customerIndex,
        CustomerIndexEdit $customerIndexEdit,
        GiftRegistryCustomerEdit $giftRegistryCustomerEdit,
        LogoutCustomerOnFrontendStep $customerAccountLogout
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerIndex = $customerIndex;
        $this->customerIndexEdit = $customerIndexEdit;
        $this->giftRegistryCustomerEdit = $giftRegistryCustomerEdit;
        $this->customerAccountLogout = $customerAccountLogout;
    }

    /**
     * Delete gift registry from customer account(backend)
     *
     * @param GiftRegistry $giftRegistry
     * @param Customer $customer
     * @return void
     */
    public function test(GiftRegistry $giftRegistry, Customer $customer)
    {
        // Preconditions

        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $giftRegistry->persist();

        // Steps
        $this->customerIndex->open();
        $this->customerIndex->getCustomerGridBlock()->searchAndOpen(['email' => $customer->getEmail()]);
        $customerForm = $this->customerIndexEdit->getCustomerForm();
        $customerForm->openTab('gift_registry');
        $filter = ['title' => $giftRegistry->getTitle()];
        $customerForm->getTab('gift_registry')->getSearchGridBlock()->searchAndOpen($filter);
        $this->giftRegistryCustomerEdit->getPageMainActions()->delete();
        $this->giftRegistryCustomerEdit->getModalBlock()->acceptAlert();
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
