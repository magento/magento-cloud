<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Invitation\Test\Block\Adminhtml\Invitation;

/**
 * Invitations grid on backend.
 */
class Grid extends \Magento\Backend\Test\Block\Widget\Grid
{
    /**
     * Locator value for link in action column.
     *
     * @var string
     */
    protected $editLink = 'td[class*=col-email]';

    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'id' => [
            'selector' => 'input[name="magento_invitation_id"]',
        ],
        'email' => [
            'selector' => 'input[name="email"]',
        ],
        'status' => [
            'selector' => 'select[name="status"]',
            'input' => 'strictselect',
        ],
        'invitee_group' => [
            'selector' => 'select[name="group_id"]',
            'input' => 'select',
        ],
    ];
}
