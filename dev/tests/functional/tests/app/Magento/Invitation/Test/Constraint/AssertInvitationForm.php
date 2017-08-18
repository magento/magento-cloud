<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Constraint;

use Magento\Invitation\Test\Fixture\Invitation;
use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndex;
use Magento\Invitation\Test\Page\Adminhtml\InvitationsIndexView;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that Invitation form was filled correctly.
 */
class AssertInvitationForm extends AbstractAssertForm
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that Invitation form was filled correctly: email, message, status.
     *
     * @param InvitationsIndex $invitationsIndex
     * @param InvitationsIndexView $invitationsIndexView
     * @param Invitation $invitation
     * @param string $status
     * @return void
     */
    public function processAssert(
        InvitationsIndex $invitationsIndex,
        InvitationsIndexView $invitationsIndexView,
        Invitation $invitation,
        $status
    ) {
        $invitationsIndex->open();
        $invitationGrid = $invitationsIndex->getInvitationGrid();
        $invitations = [];
        $uniqueEmails = array_unique($invitation->getEmail());
        foreach ($uniqueEmails as $email) {
            $invitationGrid->search(['email' => $email]);
            $rowsData = $invitationGrid->getRowsData(['id', 'email']);
            foreach ($rowsData as $rowData) {
                $invitations[] = $rowData;
            }
        }

        foreach ($invitations as $invitationData) {
            $filter = [
                'id' => $invitationData['id'],
                'email' => $invitationData['email'],
                'status' => $status,
            ];
            $invitationsIndex->getInvitationGrid()->searchAndOpen($filter);
            $fixtureData = [
                'email' => $invitationData['email'],
                'message' => $invitation->getMessage(),
                'status' => $status,
            ];
            $formData = $invitationsIndexView->getInvitationForm()->getData();
            $error = $this->verifyData($fixtureData, $formData);
            \PHPUnit_Framework_Assert::assertEmpty($error, $error);
            $invitationsIndexView->getFormPageActions()->back();
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Invitation data on View Invitation page on backend equals to passed from fixture.';
    }
}
