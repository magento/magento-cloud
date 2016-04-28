<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Form;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;

/**
 * Class Register
 * Register new customer on Frontend
 */
class Register extends \Magento\Customer\Test\Block\Form\Register
{
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
