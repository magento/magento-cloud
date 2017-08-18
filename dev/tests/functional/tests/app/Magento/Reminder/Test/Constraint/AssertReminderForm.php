<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\Constraint;

use Magento\Reminder\Test\Fixture\Reminder;
use Magento\Reminder\Test\Page\Adminhtml\ReminderIndex;
use Magento\Reminder\Test\Page\Adminhtml\ReminderView;
use Magento\Mtf\Constraint\AbstractAssertForm;

/**
 * Assert that reminder data on edit page equals passed from fixture.
 */
class AssertReminderForm extends AbstractAssertForm
{
    /**
     * Skipped fields for verify data.
     *
     * @var array
     */
    protected $skippedFields = [
        'rule_id',
        'conditions_serialized',
    ];

    /**
     * Assert that reminder data on edit page equals passed from fixture.
     *
     * @param ReminderIndex $reminderIndex
     * @param ReminderView $reminderView
     * @param Reminder $reminder
     * @return void
     */
    public function processAssert(ReminderIndex $reminderIndex, ReminderView $reminderView, Reminder $reminder)
    {
        $reminderIndex->open();
        $reminderIndex->getRemindersGrid()->searchAndOpen(['name' => $reminder->getName()]);

        $fixtureData = $reminder->getData();
        $pageData = $reminderView->getReminderForm()->getData($reminder);
        \PHPUnit_Framework_Assert::assertEquals(
            $this->prepareData($fixtureData),
            $this->prepareData($pageData),
            'Reminder data on edit page does not equals passed from fixture.'
        );
    }

    /**
     * Prepare data for compare.
     *
     * @param array $data
     * @return array
     */
    protected function prepareData(array $data)
    {
        if (isset($data['from_date'])) {
            $data['from_date'] = date('m/d/Y', strtotime($data['from_date']));
        }
        if (isset($data['to_date'])) {
            $data['to_date'] = date('m/d/Y', strtotime($data['to_date']));
        }
        return array_diff_key($data, array_flip($this->skippedFields));
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Reminder data on edit page equals passed from fixture.';
    }
}
