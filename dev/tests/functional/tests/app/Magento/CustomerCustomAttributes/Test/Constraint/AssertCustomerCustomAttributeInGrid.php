<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerCustomAttribute;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerCustomAttributeInGrid
 * Assert that created Customer Attribute can be found in grid
 */
class AssertCustomerCustomAttributeInGrid extends AbstractConstraint
{
    /**
     * Assert that created Customer Attribute can be found in grid via:
     * label, code, required, is_user_defined, visibility, sort order
     *
     * @param CustomerCustomAttribute $customerAttribute
     * @param CustomerAttributeIndex $customerAttributeIndex
     * @param CustomerCustomAttribute $initialCustomerAttribute
     * @return void
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function processAssert(
        CustomerCustomAttribute $customerAttribute,
        CustomerAttributeIndex $customerAttributeIndex,
        CustomerCustomAttribute $initialCustomerAttribute = null
    ) {
        $data = ($initialCustomerAttribute === null)
            ? $customerAttribute->getData()
            : array_merge($initialCustomerAttribute->getData(), $customerAttribute->getData());
        $filter = [
            'frontend_label' => $data['frontend_label'],
            'attribute_code' => $data['attribute_code'],
            'sort_order' => $data['sort_order'],
            'is_required' => isset($data['scope_is_required']) ? $data['scope_is_required'] : null,
            'is_user_defined' => isset($data['is_user_defined']) ? $data['is_user_defined'] : null,
            'is_visible' => isset($data['scope_is_visible']) ? $data['scope_is_visible'] : null,
        ];

        \PHPUnit_Framework_Assert::assertTrue(
            $customerAttributeIndex->getCustomerCustomAttributesGrid()->isRowVisible($filter, true, false),
            "Customer Attribute with label '" . $filter['frontend_label'] . "' is absent in Customer Attributes grid."
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Attribute is present in grid.';
    }
}
