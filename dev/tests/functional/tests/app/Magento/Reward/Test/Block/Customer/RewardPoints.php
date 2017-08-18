<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block\Customer;

use Magento\Reward\Test\Block\Customer\RewardPoints\RewardPointsInformation;
use Magento\Reward\Test\Block\Customer\RewardPoints\Subscription;
use Magento\Reward\Test\Fixture\Reward;
use Magento\Mtf\Block\Block;

/**
 * Class RewardPoints
 * Reward Points block in customer My account on frontend
 */
class RewardPoints extends Block
{
    /**
     * Reward points subscription selector
     *
     * @var string
     */
    protected $subscriptionForm = '#form-validate';

    /**
     * Reward points information block selector
     *
     * @var string
     */
    protected $rewardPointsInformation = '.block-reward-info';

    /**
     * Return reward points subscription form
     *
     * @return Subscription
     */
    protected function getSubscriptionForm()
    {
        return $this->blockFactory->create(
            \Magento\Reward\Test\Block\Customer\RewardPoints\Subscription::class,
            ['element' => $this->_rootElement->find($this->subscriptionForm)]
        );
    }

    /**
     * Return Reward points Information block
     *
     * @return RewardPointsInformation
     */
    protected function getRewardPointsInformation()
    {
        return $this->blockFactory->create(
            \Magento\Reward\Test\Block\Customer\RewardPoints\RewardPointsInformation::class,
            ['element' => $this->_rootElement->find($this->rewardPointsInformation)]
        );
    }

    /**
     * Get current reward exchange rates
     *
     * @return string
     */
    public function getRewardRatesMessages()
    {
        return $this->getRewardPointsInformation()->getRewardPointsRates();
    }

    /**
     * Get current reward points balance
     *
     * @return string
     */
    public function getRewardPointsBalance()
    {
        return $this->getRewardPointsInformation()->getRewardPointsBalance();
    }

    /**
     * Update subscription on customer frontend account
     *
     * @param Reward $reward
     * @return void
     */
    public function updateSubscription(Reward $reward)
    {
        $this->getSubscriptionForm()->fill($reward);
        $this->getSubscriptionForm()->clickSaveButton();
    }
}
