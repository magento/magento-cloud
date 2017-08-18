<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Constraint;

use Magento\Reminder\Test\Fixture\Reminder;
use Magento\Reminder\Test\Page\Adminhtml\ReminderIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert reminder present in grid.
 */
class AssertReminderInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert reminder with following fields is present in grid:
     * - Rule
     * - Start
     * - End
     * - Status
     * - Website
     *
     * @param ReminderIndex $reminderIndex
     * @param Reminder $reminder
     * @return void
     */
    public function processAssert(ReminderIndex $reminderIndex, Reminder $reminder)
    {
        $websiteIds = $reminder->getWebsiteIds();
        $filter = array_filter([
            'name' => $reminder->getName(),
            'from_date_to' => date('M j, Y', strtotime($reminder->getFromDate())),
            'to_date_to' => date('M j, Y', strtotime($reminder->getToDate())),
            'status' => $reminder->getIsActive(),
            'website' => is_array($websiteIds) ? reset($websiteIds) : null,
        ]);

        $reminderIndex->open();
        $reminderIndex->getRemindersGrid()->search($filter);
        \PHPUnit_Framework_Assert::assertTrue(
            $reminderIndex->getRemindersGrid()->isRowVisible($filter, false, false),
            'Reminder with name "' . $filter['name'] . '", is absent in grid.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Reminder present in grid.';
    }
}
