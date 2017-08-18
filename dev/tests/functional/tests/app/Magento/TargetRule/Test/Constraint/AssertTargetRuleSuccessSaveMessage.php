<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\TargetRule\Test\Constraint;

use Magento\TargetRule\Test\Page\Adminhtml\TargetRuleIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertTargetRuleSuccessSaveMessage
 */
class AssertTargetRuleSuccessSaveMessage extends AbstractConstraint
{
    const SUCCESS_MESSAGE = 'You saved the rule.';

    /**
     * Assert that success message is displayed after target rule save
     *
     * @param TargetRuleIndex $targetRuleIndex
     * @return void
     */
    public function processAssert(TargetRuleIndex $targetRuleIndex)
    {
        $actualMessages = $targetRuleIndex->getMessagesBlock()->getSuccessMessage();
        if (!is_array($actualMessages)) {
            $actualMessages = [$actualMessages];
        }
        \PHPUnit_Framework_Assert::assertContains(
            self::SUCCESS_MESSAGE,
            $actualMessages,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . implode(',', $actualMessages)
        );
    }

    /**
     * Text success save message is displayed
     *
     * @return string
     */
    public function toString()
    {
        return 'Target rule success save message is present.';
    }
}
