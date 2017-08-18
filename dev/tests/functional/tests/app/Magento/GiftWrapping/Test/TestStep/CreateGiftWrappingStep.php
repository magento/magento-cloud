<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\GiftWrapping\Test\TestStep;

use Magento\GiftWrapping\Test\Fixture\GiftWrapping;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Create Gift Wrapping using handler.
 */
class CreateGiftWrappingStep implements TestStepInterface
{
    /**
     * GiftWrapping fixture.
     *
     * @var GiftWrapping
     */
    private $giftWrapping;

    /**
     * @constructor
     * @param GiftWrapping $giftWrapping
     */
    public function __construct(GiftWrapping $giftWrapping)
    {
        $this->giftWrapping = $giftWrapping;
    }

    /**
     * Create Gift Wrapping.
     *
     * @return array
     */
    public function run()
    {
        $this->giftWrapping->persist();

        return ['giftWrapping' => $this->giftWrapping];
    }
}
