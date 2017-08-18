<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Adminhtml\Order;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;

/**
 * Class Create
 * Adminhtml sales order create block
 */
class Create extends \Magento\Sales\Test\Block\Adminhtml\Order\Create
{
    /**
     * Locator for customer attribute on New Order page
     *
     * @var string
     */
    protected $customerAttribute = "[name='order[account][%s]']";

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
