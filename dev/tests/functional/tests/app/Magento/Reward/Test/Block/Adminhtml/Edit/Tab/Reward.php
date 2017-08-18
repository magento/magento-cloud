<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block\Adminhtml\Edit\Tab;

use Magento\Backend\Test\Block\Widget\Tab;
use Magento\Mtf\Client\Locator;

/**
 * Class Reward
 * Backend customer reward tab
 */
class Reward extends Tab
{
    /**
     * Reward history accordion link selector
     *
     * @var string
     */
    protected $rewardHistorySelector = '#dt-reward_points_history';

    /**
     * Reward Points History grid selector
     *
     * @var string
     */
    protected $rewardHistoryGridSelector = '[data-grid-id="rewardPointsHistoryGrid"]';

    /**
     * Reward points grid row XPath.
     *
     * @var string
     */
    private $rewardPointsGridRow = '//div[@id="rewardPointsBalanceGrid"]//td[%s]';

    /**
     * Get customer's reward points history grid
     *
     * @return \Magento\Reward\Test\Block\Adminhtml\Edit\Tab\Reward\Grid
     */
    public function getHistoryGrid()
    {
        return $this->blockFactory->create(
            \Magento\Reward\Test\Block\Adminhtml\Edit\Tab\Reward\Grid::class,
            ['element' => $this->_rootElement->find($this->rewardHistoryGridSelector)]
        );
    }

    /**
     * Show Reward Points History Grid
     *
     * @return void
     */
    public function showRewardPointsHistoryGrid()
    {
        $element = $this->_rootElement;
        $grid = $this->rewardHistoryGridSelector;

        if (!$this->_rootElement->find($this->rewardHistorySelector . ".open")->isVisible()) {
            $this->_rootElement->find($this->rewardHistorySelector . " > a")->click();
            $this->_rootElement->waitUntil(
                function () use ($element, $grid) {
                    return $element->find($grid)->isVisible() ? true : null;
                }
            );
        }
    }

    /**
     * Return store credit status.
     *
     * @param $rowNumber
     * @return string
     */
    public function getRewardPointsGridRow($rowNumber)
    {
        $row = sprintf($this->rewardPointsGridRow, $rowNumber);
        return $this->_rootElement->find($row, Locator::SELECTOR_XPATH)->getText();
    }
}
