<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftCardAccount\Test\TestStep;

use Magento\GiftCardAccount\Test\Fixture\GiftCardAccount;
use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Apply Gift Card Account on backend order creation.
 */
class ApplyGiftCardAccountOnBackendStep implements TestStepInterface
{
    /**
     * Sales order create index page.
     *
     * @var OrderCreateIndex
     */
    protected $orderCreateIndex;

    /**
     * Gift card account.
     *
     * @var GiftCardAccount
     */
    protected $giftCardAccount;

    /**
     * @constructor
     * @param OrderCreateIndex $orderCreateIndex
     * @param GiftCardAccount|null $giftCardAccount
     */
    public function __construct(OrderCreateIndex $orderCreateIndex, GiftCardAccount $giftCardAccount = null)
    {
        $this->orderCreateIndex = $orderCreateIndex;
        $this->giftCardAccount = $giftCardAccount;
    }

    /**
     * Apply gift card.
     *
     * @return void
     */
    public function run()
    {
        if ($this->giftCardAccount !== null) {
            $this->orderCreateIndex->getGiftCardAccountBlock()->applyGiftCardAccount($this->giftCardAccount);
        }
    }
}
