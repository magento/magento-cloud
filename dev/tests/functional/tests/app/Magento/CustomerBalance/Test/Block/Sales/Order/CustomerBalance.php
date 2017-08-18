<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Sales\Order;

use Magento\Mtf\Block\Form;

/**
 * Apply store credit payment.
 */
class CustomerBalance extends Form
{
    /**
     * Apply store credit.
     *
     * @param array $payment
     * @return void
     */
    public function fillStoreCredit(array $payment)
    {
        $mapping = $this->dataMapping($payment);
        $this->_fill($mapping, null);
    }
}
