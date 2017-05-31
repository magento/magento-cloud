<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Fixture\CustomerAddressAttribute;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that created Customer Address Attribute can be found in grid.
 */
class AssertCustomerAddressAttributeInGrid extends AbstractConstraint
{
    const ERROR_MESSAGE = "Customer Address Attribute with label '%s' is absent in grid.";

    /**
     * Assert that created Customer Address Attribute can be found in grid.
     *
     * @param CustomerAddressAttribute $customerAddressAttribute
     * @param CustomerAddressAttributeIndex $customerAddressAttributeIndex
     * @return void
     */
    public function processAssert(
        CustomerAddressAttribute $customerAddressAttribute,
        CustomerAddressAttributeIndex $customerAddressAttributeIndex
    ) {
        $data = $customerAddressAttribute->getData();
        $filter = [
            'frontend_label'    => $data['frontend_label'],
            'attribute_code'    => $data['attribute_code'],
            'sort_order'        => $data['sort_order'],
            'is_required'       => isset($data['scope_is_required']) ? $data['scope_is_required'] : null,
            'is_user_defined'   => isset($data['is_user_defined'])   ? $data['is_user_defined']   : null,
            'is_visible'        => isset($data['scope_is_visible'])  ? $data['scope_is_visible']  : null,
        ];

        $isRowVisible = $customerAddressAttributeIndex->getCustomerAddressAttributesGrid()
            ->isRowVisible($filter, true, false);

        \PHPUnit_Framework_Assert::assertTrue(
            $isRowVisible,
            sprintf(self::ERROR_MESSAGE, $filter['frontend_label'])
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer Address Attribute is present in grid.';
    }
}
