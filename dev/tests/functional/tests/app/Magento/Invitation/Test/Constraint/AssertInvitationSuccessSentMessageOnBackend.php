<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Constraint;

use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success message appears after sent invitation on backend.
 */
class AssertInvitationSuccessSentMessageOnBackend extends AbstractConstraint
{
    /**
     * Success sent message.
     */
    const SUCCESS_MESSAGE = "We sent %d invitation(s).";

    /**
     * Assert that success message appears after sent invitation on frontend.
     *
     * @param InvitationsIndex $invitationsIndex
     * @param string $countSent
     * @return void
     */
    public function processAssert(InvitationsIndex $invitationsIndex, $countSent)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::SUCCESS_MESSAGE, $countSent),
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
        return 'Success send message appears on Invitations index backend page.';
    }
}
