<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assertion new item is present on the grid.
 */
class AssertItemInGrid extends AbstractConstraint
{
    /**
     * Assert that previous count vs actual increased and proves that the item was created
     *
     * @param int $existing
     * @param int $actual
     * @return void
     */
    public function processAssert($existing, $actual)
    {
        \PHPUnit_Framework_Assert::assertGreaterThan(
            $existing,
            $actual,
            'The new item created is not present on the grid.'
            . "\nExpected: " . $existing
            . "\nActual: " . $actual
        );
    }

    /**
     * Text to indicate the created item is present on the grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Assert that new created item is present on the grid.';
    }
}
