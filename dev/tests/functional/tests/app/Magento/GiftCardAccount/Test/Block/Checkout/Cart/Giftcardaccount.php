<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\Block\Checkout\Cart;

use Magento\Mtf\Block\Form;

/**
 * Class Giftcardaccount
 * Gift card account block in cart
 */
class Giftcardaccount extends Form
{
    /**
     * Add gift cards button
     *
     * @var string
     */
    protected $addGiftCardButton = '.giftcard .action.add';

    /**
     * Locator for gift card account link
     *
     * @var string
     */
    protected $giftCardsSection = '[data-role="title"]';

    /**
     * Redeem button
     *
     * @var string
     */
    protected $checkStatusAndBalance = ".action.check";

    /**
     * Fill gift card in cart
     *
     * @param string $code
     * @return void
     */
    public function addGiftCard($code)
    {
        $this->enterGiftCardCode($code);
        $this->_rootElement->find($this->addGiftCardButton)->click();
    }

    /**
     * Enter gift card code
     *
     * @param string $code
     * @return void
     */
    protected function enterGiftCardCode($code)
    {
        $this->_rootElement->find($this->giftCardsSection)->click();
        $this->_fill($this->dataMapping(['code' => $code]));
    }

    /**
     * Check status and balance
     *
     * @param string $code
     * @return void
     */
    public function checkStatusAndBalance($code)
    {
        $this->enterGiftCardCode($code);
        $this->_rootElement->find($this->checkStatusAndBalance)->click();
    }
}
