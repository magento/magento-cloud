<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerCustomAttributes\Test\Constraint;

use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeEdit;
use Magento\CustomerCustomAttributes\Test\Page\Adminhtml\CustomerAttributeIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check fields statuses (enabled/disabled) for system customer attributes.
 */
class AssertCustomerAttributesDisabled extends AbstractConstraint
{
    /**
     * Assert that system customer attributes are enabled/disabled by default.
     *
     * @param CustomerAttributeIndex $customerAttributeIndex
     * @param CustomerAttributeEdit $customerAttributeEdit
     * @param array $customerAttributes
     * @return void
     */
    public function processAssert(
        CustomerAttributeIndex $customerAttributeIndex,
        CustomerAttributeEdit $customerAttributeEdit,
        array $customerAttributes
    ) {
        foreach ($customerAttributes as $code => $value) {
            $customerAttributeIndex->open();
            $customerAttributeIndex
                ->getCustomerCustomAttributesGrid()
                ->searchAndOpen(['attribute_code' => $code]);
            \PHPUnit_Framework_Assert::assertSame(
                $value,
                $customerAttributeEdit->getCustomerCustomAttributesForm()->getFieldsStatus($value),
                'Customer attribute with code ' . $code . ' fields statuses are not correct.'
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
        return 'Customer system attributes fields are disabled/enabled according to the specified data.';
    }
}
