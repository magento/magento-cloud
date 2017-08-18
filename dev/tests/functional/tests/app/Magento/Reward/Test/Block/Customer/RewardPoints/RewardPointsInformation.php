<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block\Customer\RewardPoints;

use Magento\Mtf\Block\Block;

/**
 * Class RewardPointsInformation
 * Reward points balance Information block
 */
class RewardPointsInformation extends Block
{
    /**
     * Selector for reward current exchange rates
     *
     * @var string
     */
    protected $rewardRatesSelector = '.reward-rates';

    /**
     * Selector for current reward points balance button
     *
     * @var string
     */
    protected $rewardPointsBalanceSelector = '.reward-balance';

    /**
     * Get current reward exchange rates
     *
     * @return string
     */
    public function getRewardPointsRates()
    {
        $rates = $this->_rootElement->find($this->rewardRatesSelector)->getText();
        return trim(preg_replace('/\s+/', ' ', $rates));
    }

    /**
     * Get current reward points balance
     *
     * @return string
     */
    public function getRewardPointsBalance()
    {
        return $this->_rootElement->find($this->rewardPointsBalanceSelector)->getText();
    }
}
