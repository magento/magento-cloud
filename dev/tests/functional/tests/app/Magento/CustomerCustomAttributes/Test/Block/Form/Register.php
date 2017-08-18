<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
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

    /**
     * Get value of Customer custom Attribute
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @return string
     */
    public function getCustomerAttributeValue(CustomerCustomAttribute $customerAttribute)
    {
        return $this->_rootElement->find(
            sprintf($this->customerAttribute, $customerAttribute->getAttributeCode())
        )->getValue();
    }

    /**
     * Fixture mapping.
     *
     * @param array|null $fields
     * @param string|null $parent
     * @return array
     */
    protected function dataMapping(array $fields = null, $parent = null)
    {
        if (isset($fields['custom_attribute']['code']) && isset($fields['custom_attribute']['value'])) {
            $this->placeholders = ['attribute_code' => $fields['custom_attribute']['code']];
            $fields['custom_attribute']['value'] = MTF_TESTS_PATH . $fields['custom_attribute']['value'];
            if (isset($fields['group_id'])) {
                unset($fields['group_id']);
            }
            $this->applyPlaceholders();
        }
        return parent::dataMapping($fields, $parent);
    }
}
