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
 * Class AssertInvitationOneEntry
 * Assert only one invitation was sent to unique email on frontend
 */
class AssertInvitationOneEntry extends AbstractConstraint
{
    /**
     * Assert only one invitation was sent to unique email on frontend
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
        $this->objectManager->create(
            \Magento\Customer\Test\TestStep\LoginCustomerOnFrontendStep::class,
            ['customer' => $customer]
        )->run();
        $customerAccountIndex->getAccountMenuBlock()->openMenuItem('My Invitations');
        $email = $invitation->getEmail()['email_1'];
        \PHPUnit_Framework_Assert::assertEquals(
            1,
            $invitationIndex->getInvitationsBlock()->countInvitations($email, $status),
            "The number of invitation with email: {$email[1]} and status: {$status} is not equal to 1."
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Only one invitation was sent to unique email on frontend.';
    }
}
