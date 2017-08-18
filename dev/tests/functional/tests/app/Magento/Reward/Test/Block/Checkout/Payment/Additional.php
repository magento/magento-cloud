<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block\Checkout\Payment;

use Magento\Mtf\Block\Block;

/**
 * Checkout reward payment block.
 */
class Additional extends Block
{
    /**
     * 'Use reward points' button.
     *
     * @var string
     */
    protected $useRewardPointsButton = '[data-role="opc-use-reward"]';

    /**
     * Use reward points for the order.
     *
     * @return void
     */
    public function useRewardPoints()
    {
        $this->_rootElement->find($this->useRewardPointsButton)->click();
    }
}
