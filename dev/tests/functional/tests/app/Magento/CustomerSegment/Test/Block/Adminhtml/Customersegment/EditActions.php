<?php
/**
 * Config actions block
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Block\Adminhtml\Customersegment;

use Magento\Backend\Test\Block\FormPageActions as AbstractPageActions;

/**
 * Class EditActions
 * Edit actions block
 */
class EditActions extends AbstractPageActions
{
    /**
     * Custom "Save and Continue Edit" button
     *
     * @var string
     */
    protected $saveAndContinueButton = '#save_and_continue_edit';
}
