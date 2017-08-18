<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Select reward points on onepage checkout page.
 */
class SelectRewardPointsStep implements TestStepInterface
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
     * Preparing step properties.
     *
     * @constructor
     * @param CheckoutOnepage $checkoutOnepage
     * @param array|null $payment
     */
    public function __construct(CheckoutOnepage $checkoutOnepage, array $payment)
    {
        $this->payment = $payment;
        $this->checkoutOnepage = $checkoutOnepage;
    }

    /**
     * Select reward points.
     *
     * @return void
     */
    public function run()
    {
        if (isset($this->payment['use_reward_points'])) {
            $this->checkoutOnepage->getRewardPointsBlock()->useRewardPoints();
            $this->checkoutOnepage->getMessagesBlock()->waitSuccessMessage();
        }
    }
}
