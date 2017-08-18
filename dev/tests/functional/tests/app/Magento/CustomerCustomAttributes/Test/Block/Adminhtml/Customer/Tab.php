<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Block\Adminhtml\Customer;

/**
 * Use Tab Form Widget to specify place holders.
 */
class Tab extends \Magento\Backend\Test\Block\Widget\Tab
{
    /**
     * Fixture mapping.
     *
     * @param array|null $fields
     * @param string|null $parent
     * @return array
     */
    protected function dataMapping(array $fields = null, $parent = null)
    {
        if (isset($fields['custom_attribute'])) {
            $this->placeholders = ['attribute_code' => $fields['custom_attribute']['value']['code']];
            $this->applyPlaceholders();
        }
        return parent::dataMapping($fields, $parent);
    }
}
