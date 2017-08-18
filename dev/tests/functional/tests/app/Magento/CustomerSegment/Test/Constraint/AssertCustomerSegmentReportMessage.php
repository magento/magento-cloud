<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerSegment\Test\Constraint;

use Magento\CustomerSegment\Test\Fixture\CustomerSegment;
use Magento\CustomerSegment\Test\Page\Adminhtml\CustomerSegmentReportDetail;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertCustomerSegmentReportMessage
 * Assert that message is displayed on the customer segment report detail page
 */
class AssertCustomerSegmentReportMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Customer segments report messages
     */
    const REPORT_MESSAGES = 'Viewing combined "%s" report from segments: %s.';

    /**
     * Assert that message is displayed on the customer segment report detail page
     *
     * @param CustomerSegmentReportDetail $reportPage
     * @param array $customerSegments
     * @param array $reportActions
     * @return void
     */
    public function processAssert(
        CustomerSegmentReportDetail $reportPage,
        array $customerSegments,
        array $reportActions
    ) {
        $names = [];
        foreach ($customerSegments as $customerSegment) {
            /** @var CustomerSegment $customerSegment */
            $names[] = $customerSegment->getName();
        }
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::REPORT_MESSAGES, reset($reportActions['massaction']), implode(', ', $names)),
            $reportPage->getMessagesBlock()->getNoticeMessage(),
            'Wrong customer segment report message is displayed.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Customer segment report message is displayed correctly.';
    }
}
