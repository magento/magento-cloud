<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Worldpay\Test\Block\Sandbox;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Checkout payment block.
 */
class SecurePayment extends Block
{
    /**
     * Card details block selector.
     *
     * @var string
     */
    private $cardDetailsBlockSelector = ".//tbody[tr/*/h2[contains(text(), 'Card details')]]";

    /**
     * 'Make Payment' button selector.
     *
     * @var string
     */
    private $makePayment = "[name='op-PMMakePayment']";

    /**
     * 'Cancel' button selector.
     *
     * @var string
     */
    private $cancelPayment = ".//*[span[contains(., 'Cancel')]]/a";

    /**
     * Get Card details block.
     *
     * @return \Magento\Worldpay\Test\Block\Sandbox\SecurePayment\WorldpayCc
     */
    public function getCardDetailsBlock()
    {
        $element = $this->_rootElement->find($this->cardDetailsBlockSelector, Locator::SELECTOR_XPATH);

        return $this->blockFactory->create(
            \Magento\Worldpay\Test\Block\Sandbox\SecurePayment\WorldpayCc::class,
            ['element' => $element]
        );
    }

    /**
     * Click 'Make Payment' button.
     *
     * @return void
     */
    public function makePayment()
    {
        $this->_rootElement->find($this->makePayment)->click();
    }

    /**
     * Click 'Cancel' button.
     *
     * @return void
     */
    public function cancelPayment()
    {
        $this->_rootElement->find($this->cancelPayment, Locator::SELECTOR_XPATH)->click();
    }
}
