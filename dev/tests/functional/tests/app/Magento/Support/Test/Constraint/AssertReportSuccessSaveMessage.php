<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Test\Constraint;

use Magento\Support\Test\Page\Adminhtml\SupportReportIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assertion to check Success Save Message for Report.
 */
class AssertReportSuccessSaveMessage extends AbstractConstraint
{
    /**
     * String that has to be present in the success message
     */
    const SUCCESS_MESSAGE = 'The system report has been generated.';

    /**
     * Asserts that the success save message in the report page equals to the corespondent string
     *
     * @param SupportReportIndex $reportIndex
     * @return void
     */
    public function processAssert(SupportReportIndex $reportIndex)
    {
        $actualMessage = $reportIndex->getMessagesBlock()->getSuccessMessage();
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_MESSAGE,
            $actualMessage,
            'Wrong success message is displayed.'
            . "\nExpected: " . self::SUCCESS_MESSAGE
            . "\nActual: " . $actualMessage
        );
    }

    /**
     * Text for success save message is displayed
     *
     * @return string
     */
    public function toString()
    {
        return 'Assert that success message is displayed.';
    }
}
