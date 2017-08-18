<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\TestStep;

use Magento\Sales\Test\Page\Adminhtml\OrderCreateIndex;
use Magento\Mtf\TestStep\TestStepInterface;
use Magento\GiftWrapping\Test\Fixture\GiftWrapping;

/**
 * Select GiftWrapping Design in Create Order Page.
 */
class SelectGiftWrappingForOrderStep implements TestStepInterface
{
    /**
     * Sales order create index page.
     *
     * @var OrderCreateIndex
     */
    private $orderCreateIndex;

    /**
     * GiftWrapping fixture.
     *
     * @var GiftWrapping
     */
    private $giftWrapping;

    /**
     * @param OrderCreateIndex $orderCreateIndex
     * @param GiftWrapping $giftWrapping
     */
    public function __construct(
        OrderCreateIndex $orderCreateIndex,
        GiftWrapping $giftWrapping = null
    ) {
        $this->orderCreateIndex = $orderCreateIndex;
        $this->giftWrapping = $giftWrapping;
    }

    /**
     * Select GiftWrapping design.
     *
     * @return void
     */
    public function run()
    {
        $this->orderCreateIndex->getGiftOptionsBlock()->selectGiftWrappingDesign($this->giftWrapping);
    }
}
