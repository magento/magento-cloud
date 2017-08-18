<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeEdit;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAddressAttributeIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check fields statuses (enabled/disabled) for system customer address attributes.
 */
class AssertCustomerAddressAttributesDisabled extends AbstractConstraint
{
    /**
     * Assert that system customer address attributes are enabled/disabled by default.
     *
     * @param CustomerAddressAttributeIndex $customerAddressAttributeIndex
     * @param CustomerAddressAttributeEdit $customerAddressAttributeEdit
     * @param array $addressAttributes
     * @return void
     */
    public function processAssert(
        CustomerAddressAttributeIndex $customerAddressAttributeIndex,
        CustomerAddressAttributeEdit $customerAddressAttributeEdit,
        array $addressAttributes
    ) {
        foreach ($addressAttributes as $code => $value) {
            $customerAddressAttributeIndex->open();
            $customerAddressAttributeIndex
                ->getCustomerAddressAttributesGrid()
                ->searchAndOpen(['attribute_code' => $code]);
            \PHPUnit_Framework_Assert::assertSame(
                $value,
                $customerAddressAttributeEdit->getCustomerAddressAttributesForm()->getFieldsStatus($value),
                'Customer address attribute with code ' . $code . ' fields statuses are not correct.'
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer address system attributes fields are disabled/enabled according to the specified data.';
    }
}
