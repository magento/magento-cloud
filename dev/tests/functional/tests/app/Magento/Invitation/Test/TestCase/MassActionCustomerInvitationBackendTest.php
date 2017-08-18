<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\TestCase;

use Magento\Invitation\Test\Fixture\Invitation;
use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create two Invitations.
 *
 * Steps:
 * 1. Open Backend.
 * 2. Navigate to Marketing -> Invitations.
 * 3. Select invitation from preconditions.
 * 4. Fill data according to dataset.
 * 5. Click Submit.
 * 6. Perform all assertions.
 *
 * @group Invitations
 * @ZephyrId MAGETWO-29791
 */
class MassActionCustomerInvitationBackendTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const STABLE = 'no';
    /* end tags */

    /**
     * InvitationsIndex page.
     *
     * @var InvitationsIndex
     */
    protected $invitationsIndex;

    /**
     * Injection data.
     *
     * @param InvitationsIndex $invitationsIndex
     * @return void
     */
    public function __inject(InvitationsIndex $invitationsIndex)
    {
        $this->invitationsIndex = $invitationsIndex;
    }

    /**
     * Mass action customer invitation backend test.
     *
     * @param Invitation $invitation
     * @param string $action
     * @return void
     */
    public function test(Invitation $invitation, $action)
    {
        // Precondition
        $invitation->persist();

        // Steps
        $this->invitationsIndex->open();
        $invitationsToSelect = [];
        foreach ($invitation->getEmail() as $email) {
            $invitationsToSelect[] = ['email' => $email];
        }
        $acceptAlert = $action == 'Discard Selected' ? true : false;
        $this->invitationsIndex->getInvitationGrid()->massaction($invitationsToSelect, $action, $acceptAlert);
    }
}
