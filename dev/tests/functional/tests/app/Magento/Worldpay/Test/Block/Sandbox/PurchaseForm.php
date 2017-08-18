<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Worldpay\Test\Block\Sandbox;

use Magento\Mtf\Block\Block;

/**
 * Credit card type block.
 */
class PurchaseForm extends Block
{
    /**
     * Credit card type selector.
     *
     * @var string
     */
    protected $creditCardTypeSelector = "[name='op-DPChoose-%s^SSL']";

    /**
     * Select credit card type.
     * Possible options for $type: VISA, MAESTRO, AMEX, ECMC(Mastercard), DINERS, JCB.
     *
     * @param string $type
     * @return void
     */
    public function selectCreditCardType($type)
    {
        $this->_rootElement->find(sprintf($this->creditCardTypeSelector, $type))->click();
    }
}
