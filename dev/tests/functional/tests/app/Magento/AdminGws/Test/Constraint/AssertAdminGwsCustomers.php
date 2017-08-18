<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\AdminGws\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\Adminhtml\CustomerIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\User\Test\Fixture\User;
use Magento\Mtf\TestStep\TestStepFactory;
use Magento\Mtf\Fixture\FixtureFactory;

/**
 * Create 2 customers in different websites, login with custom admin user
 * and verify that customers are visible/not visible in grid according to AdminGws role settings.
 */
class AssertAdminGwsCustomers extends AbstractConstraint
{
    /**
     * Asserts customers visibility in customers grid.
     *
     * @param TestStepFactory $testStepFactory
     * @param FixtureFactory $fixtureFactory
     * @param Customer $customer
     * @param User $customAdmin
     * @param CustomerIndex $customerIndexPage
     * @param string $visibleCustomer
     * @return void
     */
    public function processAssert(
        TestStepFactory $testStepFactory,
        FixtureFactory $fixtureFactory,
        Customer $customer,
        User $customAdmin,
        CustomerIndex $customerIndexPage,
        $visibleCustomer
    ) {
        $customer->persist();
        $adminGwsRole = $customAdmin->getDataFieldConfig('role_id')['source']->getRole();
        $website = $adminGwsRole->getDataFieldConfig('gws_stores')['source']->getWebsites()[0];
        $visibleCustomer = $fixtureFactory->createByCode(
            'customer',
            [
                'dataset' => $visibleCustomer,
                'data' => [
                    'website_id' => ['website' => $website],
                ]
            ]
        );
        $visibleCustomer->persist();
        $testStepFactory->create(
            \Magento\User\Test\TestStep\LoginUserOnBackendStep::class,
            ['user' => $customAdmin]
        )->run();
        $customerIndexPage->open();
        \PHPUnit_Framework_Assert::assertFalse(
            $customerIndexPage->getCustomerGridBlock()->isRowVisible(['email' => $customer->getEmail()]),
            'Customer with email ' . $customer->getEmail() . ' is present in customers grid.'
        );
        \PHPUnit_Framework_Assert::assertTrue(
            $customerIndexPage->getCustomerGridBlock()->isRowVisible(['email' => $visibleCustomer->getEmail()]),
            'Customer with email ' . $visibleCustomer->getEmail() . ' is absent in customers grid.'
        );
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customers are visible in customers grid according to AdminGws role settings.';
    }
}
