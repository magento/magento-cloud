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
 * Assert that notice message appears after sending invitation on backend.
 */
class AssertInvitationNoticeMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    // @codingStandardsIgnoreStart
    /**
     * Notice message.
     */
    const NOTICE_MESSAGE = '%d invitation(s) were not sent, because customer accounts already exist for these email addresses.';
    // @codingStandardsIgnoreEnd

    /**
     * Assert that notice message appears after sending invitation on backend.
     *
     * @param InvitationsIndex $invitationsIndex
     * @param string $countNotSent
     * @return void
     */
    public function processAssert(InvitationsIndex $invitationsIndex, $countNotSent)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::NOTICE_MESSAGE, $countNotSent),
            $invitationsIndex->getMessagesBlock()->getNoticeMessage(),
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
        return 'Notice message appears on Invitations index backend page.';
    }
}
