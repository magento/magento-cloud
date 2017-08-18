<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Block\Customer;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * List customer details block in "My invitations".
 */
class ListCustomer extends Block
{
    /**
     * Locator for 'Send Invitations' button.
     *
     * @var string
     */
    protected $sendInvitationsButton = '.action.send';

    /**
     * Locator for email and its status.
     *
     * @var string
     */
    protected $invitationRow = '//tr[td[contains(.,"%s")]][td[contains(.,"%s")]]';

    /**
     * Locator for sent invitation email grid.
     *
     * @var string
     */
    protected $invitationGrid = '#invitations-list-table';

    /**
     * Click 'Send Invitations' button.
     *
     * @return void
     */
    public function sendInvitations()
    {
        $this->_rootElement->find($this->sendInvitationsButton)->click();
    }

    /**
     * Get available emails on My invitation grid.
     *
     * @param array $emails
     * @param string $status
     * @return array
     */
    public function getAvailableEmails(array $emails, $status)
    {
        $this->waitInvitationGridToLoad();
        $availableEmails = [];
        foreach ($emails as $key => $value) {
            $email = $this->_rootElement->find(sprintf($this->invitationRow, $value, $status), Locator::SELECTOR_XPATH);
            if ($email->isVisible()) {
                $availableEmails[$key] = $value;
            }
        }
        return $availableEmails;
    }

    /**
     * Get number of invitations from invitations grid on frontend.
     *
     * @param string $email
     * @param string $status
     * @return int
     */
    public function countInvitations($email, $status)
    {
        $this->waitInvitationGridToLoad();
        $invitationRow = sprintf($this->invitationRow, $email, $status);
        return count($this->_rootElement->getElements($invitationRow, Locator::SELECTOR_XPATH));
    }

    /**
     * Wait sent invitation email grid to load.
     *
     * @return void
     */
    protected function waitInvitationGridToLoad()
    {
        $browser = $this->_rootElement;
        $selector = $this->invitationGrid;
        $browser->waitUntil(
            function () use ($browser, $selector) {
                $element = $browser->find($selector);
                return $element->isVisible() ? true : null;
            }
        );
    }
}
