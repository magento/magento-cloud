<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Constraint;

use Magento\Invitation\Test\Fixture\Invitation;
use Magento\Invitation\Test\Page\InvitationIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertInvitationFrontendSuccessSentMessage
 * Assert that success message appears after sent invitation on frontend
 */
class AssertInvitationFrontendSuccessSentMessage extends AbstractConstraint
{
    /**
     * Success sent message
     */
    const SUCCESS_MESSAGE = "You sent the invitation for %s.";

    /**
     * Assert that success message appears after sent invitation on frontend
     *
     * @param Invitation $invitation
     * @param InvitationIndex $invitationIndex
     * @return void
     */
    public function processAssert(Invitation $invitation, InvitationIndex $invitationIndex)
    {
        $expectedMessages = [];
        foreach ($invitation->getEmail() as $email) {
            $expectedMessages[] = sprintf(self::SUCCESS_MESSAGE, $email);
        }
        \PHPUnit_Framework_Assert::assertEquals(
            $expectedMessages,
            $invitationIndex->getMessagesBlock()->getSuccessMessages(),
            "Expected success messages doesn't match actual."
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Success message appears on Invitation index frontend page.';
    }
}
