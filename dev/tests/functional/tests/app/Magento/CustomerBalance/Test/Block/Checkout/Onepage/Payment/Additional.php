<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Checkout\Onepage\Payment;

use Magento\Mtf\Block\Form;

/**
 * Apply store credit payment.
 */
class Additional extends Form
{
    /**
     * 'Use Store Credit' button selector.
     *
     * @var string
     */
    protected $useStoreCreditButton = '[name="payment[use_customer_balance]"]';

    /**
     * Wait element.
     *
     * @var string
     */
    protected $waitElement = '.loading-mask';

    /**
     * Apply store credit.
     *
     * @return void
     */
    public function useStoreCredit()
    {
        $this->_rootElement->find($this->useStoreCreditButton)->click();
        $this->waitForElementNotVisible($this->waitElement);
    }
}
