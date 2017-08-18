<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\TestCase;

use Magento\Invitation\Test\Fixture\Invitation;
use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndex;
use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndexNew;
use Magento\Mtf\TestCase\Injectable;

/**
 * Steps:
 * 1. Open Backend.
 * 2. Navigate to Marketing > Invitations.
 * 3. Create New Invitation.
 * 4. Fill data according to dataset.
 * 5. Save Invitation.
 * 6. Perform all assertions.
 *
 * @group Invitations
 * @ZephyrId MAGETWO-29925
 */
class CreateCustomerInvitationBackendEntityTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    /* end tags */

    /**
     * InvitationsIndex Page.
     *
     * @var InvitationsIndex
     */
    protected $invitationsIndex;

    /**
     * InvitationsIndexNew Page.
     *
     * @var InvitationsIndexNew
     */
    protected $invitationsIndexNew;

    /**
     * Injection data.
     *
     * @param InvitationsIndex $invitationsIndex
     * @param InvitationsIndexNew $invitationsIndexNew
     * @return void
     */
    public function __inject(
        InvitationsIndex $invitationsIndex,
        InvitationsIndexNew $invitationsIndexNew
    ) {
        $this->invitationsIndex = $invitationsIndex;
        $this->invitationsIndexNew = $invitationsIndexNew;
    }

    /**
     * Create customer invitation backend entity test.
     *
     * @param Invitation $invitation
     * @return void
     */
    public function test(Invitation $invitation)
    {
        $this->invitationsIndex->open();
        $this->invitationsIndex->getGridPageActions()->addNew();
        $this->invitationsIndexNew->getFormBlock()->fill($invitation);
        $this->invitationsIndexNew->getPageMainActions()->save();
    }
}
