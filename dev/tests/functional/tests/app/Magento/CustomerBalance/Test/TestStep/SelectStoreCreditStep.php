<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Select store credit on onepage checkout page.
 */
class SelectStoreCreditStep implements TestStepInterface
{
    /**
     * Array with payment methods.
     *
     * @var array
     */
    protected $payment;

    /**
     * Onepage checkout page.
     *
     * @var CheckoutOnepage
     */
    protected $checkoutOnepage;

    /**
     * @constructor
     * @param CheckoutOnepage $checkoutOnepage
     * @param array $payment
     */
    public function __construct(CheckoutOnepage $checkoutOnepage, array $payment)
    {
        $this->payment = $payment;
        $this->checkoutOnepage = $checkoutOnepage;
    }

    /**
     * Use store credit.
     *
     * @return void
     */
    public function run()
    {
        if (isset($this->payment['use_customer_balance'])) {
            $this->checkoutOnepage->getStoreCreditBlock()->useStoreCredit();
            $this->checkoutOnepage->getMessagesBlock()->waitSuccessMessage();
        }
    }
}
