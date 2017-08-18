<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Staging\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Update removal block.
 */
class UpdateDelete extends Block
{
    /**
     * "Delete the Update" radio button css selector.
     *
     * @var string
     */
    private $deleteRadioButton = '(.//*[@data-index="staging_delete_mode"])[2]';

    /**
     * Css selector for delete message.
     *
     * @var string
     */
    private $warningMessage = '.message.message-warning';

    /**
     * Clicks delete radio button.
     *
     * @return void
     */
    public function clickDelete()
    {
        $this->_rootElement->find($this->deleteRadioButton, Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Get delete message.
     *
     * @return string
     */
    public function getWarningMessage()
    {
        return $this->_rootElement->find($this->warningMessage)->getText();
    }
}
