<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Cart\Totals;

/**
 * Customer balance cart totals block.
 */
class CustomerBalance extends \Magento\Checkout\Test\Block\Cart\Totals
{
    /**
     * 'Remove' store credit link.
     *
     * @var string
     */
    protected $removeStoreCredit = '.totals.balance .delete';

    /**
     * Remove store credits from the quote.
     *
     * @return void
     */
    public function removeStoreCredit()
    {
        $this->_rootElement->find($this->removeStoreCredit)->click();
    }
}
