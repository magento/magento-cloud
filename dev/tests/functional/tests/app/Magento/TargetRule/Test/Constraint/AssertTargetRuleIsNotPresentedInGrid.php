<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Constraint;

use Magento\TargetRule\Test\Fixture\TargetRule;
use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertTargetRuleIsNotPresentedInGrid
 */
class AssertTargetRuleIsNotPresentedInGrid extends AbstractConstraint
{
    /**
     * Day in seconds
     */
    const DAY = 86400;

    /**
     * Assert that Target Rule is not presented in grid
     *
     * @param TargetRule $targetRule
     * @param TargetRuleIndex $targetRuleIndex
     * @return void
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function processAssert(
        TargetRule $targetRule,
        TargetRuleIndex $targetRuleIndex
    ) {
        $fromDate = isset($data['from_date']) ? strtotime($data['from_date']) : null;
        $filter = [
            'name' => $targetRule->getName(),
            'applies_to' => $targetRule->hasData('apply_to') ? $targetRule->getApplyTo() : null,
            'status' => $targetRule->hasData('is_active') ? $targetRule->getIsActive() : null,
        ];

        if ($fromDate) {
            $filter['start_on_from'] = date('m/d/Y', $fromDate - self::DAY);
        }
        $targetRuleIndex->open();
        $targetRuleIndex->getTargetRuleGrid()->search($filter);
        if ($fromDate) {
            $filter['start_on_from'] = date('M j, Y', $fromDate);
        }
        \PHPUnit_Framework_Assert::assertFalse(
            $targetRuleIndex->getTargetRuleGrid()->isRowVisible($filter, false),
            'Target rule with '
            . 'name \'' . $filter['name'] . '\', '
            . 'is presented in Target rule grid.'
        );
    }

    /**
     * Text success target rule is not presented in grid
     *
     * @return string
     */
    public function toString()
    {
        return 'Target rule is not presented in grid.';
    }
}
