<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Test\Constraint;

use Magento\Support\Test\Page\Adminhtml\SupportReportIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assertion no item is present on the grid.
 */
class AssertNoReportItemInGrid extends AbstractConstraint
{
    /**
     * Assert that the backup grid doesn't contain any items after mass delete all
     *
     * @param SupportReportIndex $supportReportIndex
     * @return void
     */
    public function processAssert(SupportReportIndex $supportReportIndex)
    {
        $actual = count($supportReportIndex->getReportsGridBlock()->getAllIds());
        \PHPUnit_Framework_Assert::assertEquals(
            0,
            $actual,
            'Mass delete did not delete all report elements.'
            . "\nExpected: " . 0
            . "\nActual: " . $actual
        );
    }

    /**
     * Text to validate success of deleting all items on the report grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Assert that no items are present on the grid.';
    }
}
