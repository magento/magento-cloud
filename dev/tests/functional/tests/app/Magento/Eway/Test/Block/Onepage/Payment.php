<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Eway\Test\Block\Onepage;

use Magento\Mtf\Fixture\InjectableFixture;

/**
 * @inheritdoc
 */
class Payment extends \Magento\Checkout\Test\Block\Onepage\Payment
{
    /**
     * Place order button.
     *
     * @var string
     */
    protected $placeOrder = '.payment-method._active .action.primary.checkout';

    /**
     * @inheritdoc
     */
    public function selectPaymentMethod(array $payment, InjectableFixture $creditCard = null)
    {
        $paymentSelector = sprintf($this->paymentMethodInput, $payment['method']);
        $paymentLabelSelector = sprintf($this->paymentMethodLabel, $payment['method']);

        try {
            $this->waitForElementNotVisible($this->waitElement);
            $this->waitForElementVisible($paymentLabelSelector);
        } catch (\Exception $exception) {
            throw new \Exception('Such payment method is absent.');
        }

        $paymentRadioButton = $this->_rootElement->find($paymentSelector);
        if ($paymentRadioButton->isVisible()) {
            $paymentRadioButton->click();
        }
    }
}
