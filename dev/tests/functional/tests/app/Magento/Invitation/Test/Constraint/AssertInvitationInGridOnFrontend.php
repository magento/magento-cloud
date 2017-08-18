<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Constraint;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Customer\Test\Page\CustomerAccountIndex;
use Magento\Invitation\Test\Fixture\Invitation;
use Magento\Invitation\Test\Page\InvitationIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertInvitationInGridOnFrontend
 * Assert Invitation appears on frontend in My Invitations grid
 */
class AssertInvitationInGridOnFrontend extends AbstractConstraint
{
    /**
     * Assert Invitation appears on frontend in My Invitations grid
     *
     * @param Customer $customer
     * @param Invitation $invitation
     * @param CustomerAccountIndex $customerAccountIndex
     * @param InvitationIndex $invitationIndex
     * @param string $status
     * @return void
     */
    public function processAssert(
        Customer $customer,
        Invitation $invitation,
        CustomerAccountIndex $customerAccountIndex,
        InvitationIndex $invitationIndex,
        $status
    ) {
        $loginCustomerOnFrontendStep = $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        );
        $loginCustomerOnFrontendStep->run();
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Invitations');
        $expectedData = $invitation->getEmail();
        $actualData = $invitationIndex->getInvitationsBlock()->getAvailableEmails($expectedData, $status);
        \PHPUnit_Framework_Assert::assertEquals(
            $expectedData,
            $actualData,
            "Expected and actual emails are not equal."
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Invitation appears in My invitation grid on frontend.';
    }
}
