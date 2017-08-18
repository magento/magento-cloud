<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftRegistry\Test\Block\Customer;

use Magento\Mtf\Block\Form;

/**
 * Form for share gift registry.
 */
class Share extends Form
{
    /**
     * Sender message selector.
     *
     * @var string
     */
    protected $senderMessage = '[name="sender_message"]';

    /**
     * "Share Gift Registry" button selector.
     *
     * @var string
     */
    protected $shareGiftRegistry = '.share';

    /**
     * Add recipient button selector.
     *
     * @var string
     */
    protected $addRecipient = '#add-recipient-button';

    /**
     * Recipient block selector.
     *
     * @var string
     */
    protected $recipient = '#row%d';

    /**
     * Click "Share Gift Registry" button.
     *
     * @return void
     */
    public function shareGiftRegistry()
    {
        $this->_rootElement->find($this->shareGiftRegistry)->click();
    }

    /**
     * Fill share gift registry form.
     *
     * @param string $message
     * @param array $recipients
     * @return void
     */
    public function fillForm($message, array $recipients)
    {
        $this->_rootElement->find($this->senderMessage)->setValue($message);
        foreach ($recipients as $key => $recipient) {
            if ($key !== 0) {
                $this->_rootElement->find($this->addRecipient)->click();
            }
            $element = $this->_rootElement->find(sprintf($this->recipient, $key));
            $mapping = $this->dataMapping($recipient);
            $this->_fill($mapping, $element);
        }
    }
}
