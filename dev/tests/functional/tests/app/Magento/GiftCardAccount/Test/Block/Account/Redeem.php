<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Block\Account;

use Magento\Mtf\Block\Block;

/**
 * Redeem block on customer account page.
 */
class Redeem extends Block
{
    /**
     * Gift card code input field.
     *
     * @var string
     */
    protected $giftCardCode = '[name="giftcard_code"]';

    /**
     * Redeem button.
     *
     * @var string
     */
    protected $redeemGiftCard = ".action.redeem";

    /**
     * Redeem button.
     *
     * @var string
     */
    protected $checkStatusAndBalance = ".action.check";

    /**
     * Waiter block css selector.
     *
     * @var string
     */
    protected $waiter = '#gc-please-wait';

    /**
     * Fill gift card redeem.
     *
     * @param string $value
     * @return void
     */
    public function redeemGiftCard($value)
    {
        $this->enterGiftCardCode($value);
        $this->_rootElement->find($this->redeemGiftCard)->click();
    }

    /**
     * Check status and balance.
     *
     * @param string $value
     * @return void
     */
    public function checkStatusAndBalance($value)
    {
        $this->enterGiftCardCode($value);
        $this->_rootElement->find($this->checkStatusAndBalance)->click();
        $this->waitForElementNotVisible($this->waiter);
    }

    /**
     * Enter gift card code.
     *
     * @param string $value
     * @return void
     */
    protected function enterGiftCardCode($value)
    {
        $this->_rootElement->find($this->giftCardCode)->setValue($value);
    }
}
