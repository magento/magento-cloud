<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Constraint;

use Magento\Invitation\Test\Page\InvitationIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that notice message appears after sent invitation to the same email address.
 */
class AssertInvitationFrontendNoticeSendDuplicateMessage extends AbstractConstraint
{
    /**
     * Notice duplicate message
     */
    const NOTICE_MESSAGE = "We did not send 1 invitation(s) addressed to current customers.";

    /**
     * Assert that notice message appears after sent invitation to the same email address.
     *
     * @param InvitationIndex $invitationIndex
     * @return void
     */
    public function processAssert(InvitationIndex $invitationIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::NOTICE_MESSAGE,
            $invitationIndex->getMessagesBlock()->getNoticeMessage(),
            "Expected notice message doesn't match actual."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Notice message appears on Invitation index frontend page.';
    }
}
