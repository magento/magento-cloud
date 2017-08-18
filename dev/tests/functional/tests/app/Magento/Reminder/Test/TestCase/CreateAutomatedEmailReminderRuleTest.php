<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Reminder\Test\TestCase;

use Magento\Customer\Test\Fixture\Customer;
use Magento\Reminder\Test\Fixture\Reminder;
use Magento\Reminder\Test\Page\Adminhtml\ReminderIndex;
use Magento\Reminder\Test\Page\Adminhtml\ReminderView;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Create customer1 and customer2
 * 2. Create Product with price 100
 *
 * Steps:
 * 1. Login to backend
 * 2. Go to Marketing > Email Reminders
 * 3. Create new reminder
 * 4. Fill data from dataset
 * 5. Save Reminder
 * 6. Perform all assertions
 *
 * @group Email_Reminder
 * @ZephyrId MAGETWO-29790
 */
class CreateAutomatedEmailReminderRuleTest extends Injectable
{
    /* tags */
    const MVP = 'yes';
    const TEST_TYPE = 'extended_acceptance_test';
    /* end tags */

    /**
     * Email Reminder grid page.
     *
     * @var ReminderIndex
     */
    protected $reminderIndex;

    /**
     * Email Reminder view page.
     *
     * @var ReminderView
     */
    protected $reminderView;

    /**
     * Prepare data.
     *
     * @param FixtureFactory $fixtureFactory
     * @return array
     */
    public function __prepare(FixtureFactory $fixtureFactory)
    {
        $product = $fixtureFactory->createByCode('catalogProductSimple', ['dataset' => 'product_100_dollar']);
        $product->persist();
        return ['product' => $product];
    }

    /**
     * Inject data.
     *
     * @param ReminderIndex $reminderIndex
     * @param ReminderView $reminderView
     * @param Customer $customer1
     * @param Customer $customer2
     * @return array
     */
    public function __inject(
        ReminderIndex $reminderIndex,
        ReminderView $reminderView,
        Customer $customer1,
        Customer $customer2
    ) {
        $this->reminderIndex = $reminderIndex;
        $this->reminderView = $reminderView;

        $customer1->persist();
        $customer2->persist();
        return [
            'customer1' => $customer1,
            'customer2' => $customer2
        ];
    }

    /**
     * Run create automated email reminder rule test.
     *
     * @param Reminder $reminder
     * @reutrn void
     */
    public function test(Reminder $reminder)
    {
        $this->reminderIndex->open();
        $this->reminderIndex->getPageActionsBlock()->addNew();
        $this->reminderView->getReminderForm()->fill($reminder);
        $this->reminderView->getPageMainActions()->save();
    }
}
