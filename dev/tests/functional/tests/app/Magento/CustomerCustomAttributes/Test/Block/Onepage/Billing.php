<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Onepage;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;

/**
 * Class Billing
 * One page checkout billing block on frontend
 */
class Billing extends \Magento\Checkout\Test\Block\Onepage\Payment\Method\Billing
{
    /**
     * Locator for customer attribute on Checkout page
     *
     * @var string
     */
    protected $customerAttribute = "[name='billing[%s]']";

    /**
     * Check if Customer custom Attribute visible
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @return bool
     */
    public function isCustomerAttributeVisible(CustomerCustomAttribute $customerAttribute)
    {
        return $this->_rootElement->find(
            sprintf($this->customerAttribute, $customerAttribute->getAttributeCode())
        )->isVisible();
    }
}
