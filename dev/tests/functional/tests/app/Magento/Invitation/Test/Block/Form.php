<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Block;

use Magento\Mtf\Fixture\FixtureInterface;
use Magento\Mtf\Client\Element\SimpleElement;

/**
 * Class Form
 * Send Invitations form
 */
class Form extends \Magento\Mtf\Block\Form
{
    /**
     * Send Invitations button
     *
     * @var string
     */
    protected $sendInvitationsButton = '.action.submit';

    /**
     * Add email button
     *
     * @var string
     */
    protected $addEmail = '.add';

    /**
     * Click 'Send Invitations' button
     *
     * @return void
     */
    public function sendInvitations()
    {
        $this->_rootElement->find($this->sendInvitationsButton)->click();
    }

    /**
     * Fill form
     *
     * @param FixtureInterface $invitation
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fill(FixtureInterface $invitation, SimpleElement $element = null)
    {
        $data = $invitation->getData();
        $mapping = $this->dataMapping($data);
        $emailCount = count($data['email']);
        while ($emailCount > 1) {
            $this->_rootElement->find($this->addEmail)->click();
            $emailCount--;
        }
        $this->_fill($mapping, $element);

        return $this;
    }
}
