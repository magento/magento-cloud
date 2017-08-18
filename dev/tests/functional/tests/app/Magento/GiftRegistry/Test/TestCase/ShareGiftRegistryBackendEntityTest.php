<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\TestCase;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndexEdit;
use Magento\Customer\Test\TestStep\LogoutCustomerOnFrontendStep;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\Adminhtml\GiftRegistryCustomerEdit;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Creation for Share Backend GiftRegistryEntity
 *
 * Test Flow:
 *
 * Preconditions:
 * 1. Customer is created on the frontend
 * 2. Gift registry is created
 *
 * Steps:
 * 1. Login to backend
 * 2. Open Customers->All Customers
 * 3. Search and open created in preconditions customer
 * 4. Open Gift Registry tab
 * 5. Open created in preconditions gift registry
 * 6. Fill "Sharing info" sections according to dataset
 * 7. Click Share Gift Registry
 * 8. Perform all assertions
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-27225
 */
class ShareGiftRegistryBackendEntityTest extends Injectable
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
     * Create customer
     *
     * @param Customer $customer
     * @return array
     */
    public function __prepare(Customer $customer)
    {
        $customer->persist();

        return [
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
     * Share Gift Registry from Customer Account(Backend)
     *
     * @param GiftRegistry $giftRegistry
     * @param Customer $customer
     * @param array $sharingInfo
     * @return void
     */
    public function test(GiftRegistry $giftRegistry, Customer $customer, $sharingInfo)
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
        $this->giftRegistryCustomerEdit->getSharingInfoBlock()->fillForm($sharingInfo);
        $this->giftRegistryCustomerEdit->getSharingInfoBlock()->shareGiftRegistry();
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
