<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Constraint;

use Magento\Invitation\Test\Fixture\Invitation;
use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertInvitationSuccessDiscardMessage.
 * Assert that success message appears after discard invitation on backend.
 */
class AssertInvitationSuccessDiscardMessage extends AbstractConstraint
{
    /**
     * Success discard message.
     */
    const SUCCESS_MESSAGE = "We discarded %d of %d invitations.";

    /**
     * Assert that success message appears after discard invitation on backend.
     *
     * @param InvitationsIndex $invitationsIndex
     * @param Invitation $invitation
     * @param string $emailsCount
     * @return void
     */
    public function processAssert(InvitationsIndex $invitationsIndex, Invitation $invitation, $emailsCount)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, $emailsCount, count($invitation->getEmail())),
            $invitationsIndex->getMessagesBlock()->getSuccessMessage(),
            "Expected success message doesn't match actual."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Success discard message appears on Invitations index backend page.';
    }
}
