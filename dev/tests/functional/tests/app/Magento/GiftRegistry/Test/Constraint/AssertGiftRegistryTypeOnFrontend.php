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
 * Class AssertGiftRegistryTypeOnFrontend
 * Assert that created Gift Registry type can be found at Customer Account > Gift Registry
 */
class AssertGiftRegistryTypeOnFrontend extends AbstractConstraint
{
    /**
     * Assert that created Gift Registry type can be found at Customer Account > Gift Registry
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

        \PHPUnit_Framework_Assert::assertTrue(
            $giftRegistryAddSelect->getGiftRegistryTypeBlock()->isGiftRegistryVisible($giftRegistryType->getLabel()),
            'Gift registry \'' . $giftRegistryType->getLabel() . '\' is not present in dropdown.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Gift Registry type was found at Customer Account > Gift Registry.';
    }
}
