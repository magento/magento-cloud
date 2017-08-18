<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountLogout;
use Magento\GiftRegistry\Test\Fixture\GiftRegistryType;
use Magento\GiftRegistry\Test\Page\Adminhtml\GiftRegistryAdminIndex as GiftRegistryIndex;
use Magento\GiftRegistry\Test\Page\Adminhtml\GiftRegistryNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Steps:
 * 1. Log in to Backend
 * 2. Navigate to Stores > Gift Registry
 * 3. Click "Add Gift Registry Type"
 * 4. Fill data according to dataset
 * 5. Save gift registry
 * 6. Perform all assertions
 *
 * @group Gift_Registry
 * @ZephyrId MAGETWO-27146
 */
class CreateGiftRegistryTypeEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * GiftRegistryIndex page
     *
     * @var GiftRegistryIndex
     */
    protected $giftRegistryIndex;

    /**
     * GiftRegistryNew page
     *
     * @var GiftRegistryNew
     */
    protected $giftRegistryNew;

    /**
     * CustomerAccountLogout page
     *
     * @var CustomerAccountLogout
     */
    protected $customerAccountLogout;

    /**
     * Preparing customer for constraints
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
     * Preparing pages for test
     *
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryNew $giftRegistryNew
     * @param CustomerAccountLogout $customerAccountLogout
     * @return void
     */
    public function __inject(
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryNew $giftRegistryNew,
        CustomerAccountLogout $customerAccountLogout
    ) {
        $this->giftRegistryIndex = $giftRegistryIndex;
        $this->giftRegistryNew = $giftRegistryNew;
        $this->customerAccountLogout = $customerAccountLogout;
    }

    /**
     * Run CreateGiftRegistryTypeEntityTest
     *
     * @param GiftRegistryType $giftRegistryType
     * @return void
     */
    public function test(GiftRegistryType $giftRegistryType)
    {
        // Steps
        $this->giftRegistryIndex->open();
        $this->giftRegistryIndex->getPageActions()->addNew();
        $this->giftRegistryNew->getGiftRegistryForm()->fill($giftRegistryType);
        $this->giftRegistryNew->getPageActions()->save();
    }

    /**
     * Tear down after variation
     *
     * @return void
     */
    public function tearDown()
    {
        $this->customerAccountLogout->open();
    }
}
