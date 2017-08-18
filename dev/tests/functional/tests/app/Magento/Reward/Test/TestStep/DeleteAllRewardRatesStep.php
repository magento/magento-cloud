<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\TestStep;

use Magento\Reward\Test\Page\Adminhtml\RewardRateIndex;
use Magento\Reward\Test\Page\Adminhtml\RewardRateNew;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Delete all Reward Rates on backend.
 */
class DeleteAllRewardRatesStep implements TestStepInterface
{
    /**
     * Reward rate index page.
     *
     * @var RewardRateIndex
     */
    protected $rewardRateIndexPage;

    /**
     * Reward rate new page.
     *
     * @var RewardRateNew
     */
    protected $rewardRateNewPage;

    /**
     * @construct
     * @param RewardRateIndex $rewardRateIndexPage
     * @param RewardRateNew $rewardRateNewPage
     */
    public function __construct(RewardRateIndex $rewardRateIndexPage, RewardRateNew $rewardRateNewPage)
    {
        $this->rewardRateIndexPage = $rewardRateIndexPage;
        $this->rewardRateNewPage = $rewardRateNewPage;
    }

    /**
     * Delete Reward Rates backend.
     *
     * @return void
     */
    public function run()
    {
        $this->rewardRateIndexPage->open();
        $this->rewardRateIndexPage->getRewardRateGrid()->resetFilter();
        while ($this->rewardRateIndexPage->getRewardRateGrid()->isFirstRowVisible()) {
            $this->rewardRateIndexPage->getRewardRateGrid()->openFirstRow();
            $this->rewardRateNewPage->getFormPageActions()->delete();
            $this->rewardRateNewPage->getModalBlock()->acceptAlert();
        }
    }
}
