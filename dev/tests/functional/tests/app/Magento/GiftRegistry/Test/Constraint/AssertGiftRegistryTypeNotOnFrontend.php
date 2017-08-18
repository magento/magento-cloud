<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\GiftRegistry\Test\Fixture\GiftRegistryType;
use Magento\GiftRegistry\Test\Page\GiftRegistryAddSelect;
use Magento\GiftRegistry\Test\Page\GiftRegistryIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertGiftRegistryTypeNotOnFrontend
 * Assert that deleted Gift Registry type is absent on creation new gift registry form on my account on frontend
 */
class AssertGiftRegistryTypeNotOnFrontend extends AbstractConstraint
{
    /**
     * Assert that deleted Gift Registry type is absent on creation new gift registry form on my account on frontend
     *
     * @param Customer $customer
     * @param GiftRegistryType $giftRegistryType
     * @param CustomerAccountIndex $customerAccountIndex
     * @param GiftRegistryIndex $giftRegistryIndex
     * @param GiftRegistryAddSelect $giftRegistryAddSelect
     * @return void
     */
    public function processAssert(
        Customer $customer,
        GiftRegistryType $giftRegistryType,
        CustomerAccountIndex $customerAccountIndex,
        GiftRegistryIndex $giftRegistryIndex,
        GiftRegistryAddSelect $giftRegistryAddSelect
    ) {
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('Gift Registry');
        $giftRegistryIndex->getActionsToolbar()->addNew();

        \PHPUnit_Framework_Assert::assertFalse(
            $giftRegistryAddSelect->getGiftRegistryTypeBlock()->isGiftRegistryVisible($giftRegistryType->getLabel()),
            'Gift registry \'' . $giftRegistryType->getLabel() . '\' is present in dropdown.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Registry type was not found at Customer Account > Gift Registry.';
    }
}
