<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Cybersource\Test\Block\Sandbox;

use Magento\Mtf\Block\Block;

/**
 *  Transaction Response Page block.
 */
class TransactionResponse extends Block
{
    /**
     * Return url field selector.
     *
     * @var string
     */
    private $urlSelector = '[name="customURL"]';

    /**
     * Save button selector.
     *
     * @var string
     */
    private $saveButtonSelector = '#saveButtonTop';

    /**
     * Set response url. Should be in format like this:
     * https://%magento_instance_url%/cybersource/SilentOrder/TokenResponse
     *
     * @param string $url
     * @return void
     */
    public function setResponseUrl($url)
    {
        $this->_rootElement->find($this->urlSelector)->setValue($url);
    }

    /**
     * Click "Save" button.
     * @return void
     */
    public function save()
    {
        $this->_rootElement->find($this->saveButtonSelector)->click();
    }
}
