<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestStep;

use Magento\Checkout\Test\Page\CheckoutCart;
use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Apply gift card before one page checkout.
 */
class ApplyGiftCardStep implements TestStepInterface
{
    /**
     * Checkout cart page.
     *
     * @var CheckoutCart
     */
    protected $checkoutCart;

    /**
     * GiftCardAccount fixtures.
     *
     * @var GiftCardAccount[]
     */
    protected $giftCardAccounts;

    /**
     * @constructor
     * @param CheckoutCart $checkoutCart
     * @param GiftCardAccount|GiftCardAccount[] $giftCardAccount
     */
    public function __construct(CheckoutCart $checkoutCart, $giftCardAccount = null)
    {
        $this->checkoutCart = $checkoutCart;
        $this->giftCardAccounts = is_array($giftCardAccount) ? $giftCardAccount : [$giftCardAccount];
    }

    /**
     * Apply gift card before one page checkout.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->giftCardAccounts as $giftCardAccount) {
            if ($giftCardAccount !== null) {
                $this->checkoutCart->getGiftCardAccountBlock()->addGiftCard($giftCardAccount->getCode());
                $this->checkoutCart->getTotalsBlock()->waitForUpdatedTotals();
            }
        }
    }
}
