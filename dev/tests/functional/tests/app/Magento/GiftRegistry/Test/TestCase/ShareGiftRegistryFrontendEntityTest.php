<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\TestCase;

use Magento\Cms\Test\Page\CmsIndex;
use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\GiftRegistry\Test\Fixture\GiftRegistry;
use Magento\GiftRegistry\Test\Page\GiftRegistryAddSelect;
use Magento\GiftRegistry\Test\Page\GiftRegistryEdit;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\GiftRegistry\Test\Page\GiftRegistryShare;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Customer is created on the frontend.
 *
 * Steps:
 * 1. Login as registered customer to frontend.
 * 2. Create Gift Registry.
 * 3. Share Gift Registry.
 * 4. Perform all assertions.
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-27035
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShareGiftRegistryFrontendEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S2';
    /* end tags */

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
     * Gift Registry index page.
     *
     * @var GiftRegistryIndex
     */
    protected $giftRegistryIndex;

    /**
     * Gift Registry select type page.
     *
     * @var GiftRegistryAddSelect
     */
    protected $giftRegistryAddSelect;

    /**
     * Gift Registry edit type page.
     *
     * @var GiftRegistryEdit
     */
    protected $giftRegistryEdit;

    /**
     * Gift Registry share page.
     *
     * @var GiftRegistryShare
     */
    protected $giftRegistryShare;

    /**
     * Inject pages.
     *
     * @param CmsIndex $cmsIndex
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryAddSelect $giftRegistryAddSelect
     * @param GiftRegistryEdit $giftRegistryEdit
     * @param GiftRegistryShare $giftRegistryShare
     * @return void
     */
    public function __inject(
        CmsIndex $cmsIndex,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryAddSelect $giftRegistryAddSelect,
        GiftRegistryEdit $giftRegistryEdit,
        GiftRegistryShare $giftRegistryShare
    ) {
        $this->cmsIndex = $cmsIndex;
        $this->customerAccountIndex = $customerAccountIndex;
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->giftRegistryAddSelect = $giftRegistryAddSelect;
        $this->giftRegistryEdit = $giftRegistryEdit;
        $this->giftRegistryShare = $giftRegistryShare;
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
     * Sharing Frontend Gift Registry entity test.
     *
     * @param Customer $customer
     * @param array $recipients
     * @param GiftRegistry $giftRegistry
     * @param string $message
     * @return array
     */
    public function test(
        Customer $customer,
        array $recipients,
        GiftRegistry $giftRegistry,
        $message
    ) {
        // Steps
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $giftRegistry->persist();
        $this->giftRegistryIndex->getGiftRegistryGrid()->eventAction($giftRegistry->getTitle(), 'Share');
        $this->giftRegistryShare->getGiftRegistryShareForm()->fillForm($message, $recipients);
        $this->giftRegistryShare->getGiftRegistryShareForm()->shareGiftRegistry();
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
