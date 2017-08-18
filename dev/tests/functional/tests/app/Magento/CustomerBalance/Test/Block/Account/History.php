<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Account;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Class History
 * Store credit block on customer account page
 */
class History extends Block
{
    /**
     * Redeem button
     *
     * @var string
     */
    protected $balanceChange = '//*[contains(@class,"change")]/span[contains(.,"%s")]';

    /**
     * Check store credit balance history
     *
     * @param string $value
     * @return bool
     */
    public function isBalanceChangeVisible($value)
    {
        return $this->_rootElement->find(sprintf($this->balanceChange, $value), Locator::SELECTOR_XPATH)->isVisible();
    }
}
