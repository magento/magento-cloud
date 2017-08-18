<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer\Edit;

use Magento\Mtf\Block\Block;

/**
 * Class ActionsToolbar
 * Frontend gift registry edit actions toolbar
 */
class ActionsToolbar extends Block
{
    /**
     * Save button selector
     *
     * @var string
     */
    protected $saveButton = '[id="submit.save"]';

    /**
     * Click to save button
     *
     * @return void
     */
    public function save()
    {
        $this->_rootElement->find($this->saveButton)->click();
    }
}
