<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Worldpay\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\Worldpay\Test\Page\Sandbox\PaymentMethod;
use Magento\Worldpay\Test\Page\Sandbox\PaymentPage;

/**
 * Cancel order from Worldpay sandbox side.
 */
class CancelWorldpayCheckoutStep implements TestStepInterface
{
    /**
     * Onepage checkout page.
     *
     * @var CheckoutOnepage
     */
    private $checkoutOnepage;

    /**
     * Worldpay payment method page.
     *
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * Worldpay payment page.
     *
     * @var PaymentPage
     */
    private $paymentPage;

    /**
     * Credit card type.
     * Possible options: VISA, MAESTRO, AMEX, ECMC(Mastercard), DINERS, JCB.
     *
     * @var string
     */
    private $creditCardType;

    /**
     * @param CheckoutOnepage $checkoutOnepage
     * @param PaymentMethod $paymentMethod
     * @param PaymentPage $paymentPage
     * @param string $creditCardType
     */
    public function __construct(
        CheckoutOnepage $checkoutOnepage,
        PaymentMethod $paymentMethod,
        PaymentPage $paymentPage,
        $creditCardType
    ) {
        $this->checkoutOnepage = $checkoutOnepage;
        $this->paymentMethod = $paymentMethod;
        $this->paymentPage = $paymentPage;
        $this->creditCardType = $creditCardType;
    }

    /**
     * Cancel payment on Worldpay side.
     *
     * @return void
     */
    public function run()
    {
        $this->checkoutOnepage->getPaymentBlock()->placeOrder();

        $this->paymentMethod->getPurchaseForm()->selectCreditCardType(strtoupper($this->creditCardType));
        $this->paymentPage->getSecurePaymentBlock()->cancelPayment();
    }
}
