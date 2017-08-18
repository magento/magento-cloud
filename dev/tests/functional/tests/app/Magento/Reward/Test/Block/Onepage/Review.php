<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reward\Test\Block\Onepage;

/**
 * Class Review
 * One page checkout order review block
 */
class Review extends \Magento\Checkout\Test\Block\Onepage\Review
{
    /**
     * Remove reward points
     *
     * @var string
     */
    protected $removeButton = '.rewardpoints .action.delete';

    /**
     * Click on 'Remove' reward points link
     *
     * @return void
     */
    public function clickRemoveRewardPoints()
    {
        $this->_rootElement->find($this->removeButton)->click();
    }
}
