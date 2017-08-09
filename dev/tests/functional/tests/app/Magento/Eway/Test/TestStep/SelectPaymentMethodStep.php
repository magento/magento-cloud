<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Eway\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Class SelectPaymentMethodStep
 * Selecting payment method
 */
class SelectPaymentMethodStep implements TestStepInterface
{
    /**
     * Onepage checkout page.
     *
     * @var CheckoutOnepage
     */
    protected $ewayCheckoutOnepage;

    /**
     * Payment information.
     *
     * @var string
     */
    protected $payment;

    /**
     * @constructor
     * @param EwayCheckoutOnepage $checkoutOnepage
     * @param array $payment
     */
    public function __construct(
        CheckoutOnepage $ewayCheckoutOnepage,
        array $payment
    ) {
        $this->ewayCheckoutOnepage = $ewayCheckoutOnepage;
        $this->payment = $payment;
    }

    /**
     * Run step that selecting payment method.
     *
     * @return void
     */
    public function run()
    {
        $this->ewayCheckoutOnepage->getEwayPaymentBlock()->selectPaymentMethod($this->payment);
    }
}
