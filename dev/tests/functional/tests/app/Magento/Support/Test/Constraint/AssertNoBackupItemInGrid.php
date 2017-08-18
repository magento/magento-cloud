<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Test\Constraint;

use Magento\Support\Test\Page\Adminhtml\SupportBackupIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assertion no item is present on the grid.
 */
class AssertNoBackupItemInGrid extends AbstractConstraint
{
    /**
     * Assert that the backup grid doesn't contain any items after mass delete all
     *
     * @param SupportBackupIndex $supportReportIndex
     * @return void
     */
    public function processAssert(SupportBackupIndex $supportReportIndex)
    {
        $actual = count($supportReportIndex->getBackupsGridBlock()->getAllIds());
        \PHPUnit_Framework_Assert::assertEquals(
            0,
            $actual,
            'Mass delete did not delete all report elements.'
            . "\nExpected: " . 0
            . "\nActual: " . $actual
        );
    }

    /**
     * Text to validate success of deleting all items on the backup grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Assert that no items are present on the grid.';
    }
}
