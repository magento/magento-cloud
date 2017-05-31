<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\Constraint;

use Magento\Logging\Test\Page\Adminhtml\Details;
use Magento\Logging\Test\Page\Adminhtml\Logging as LoggingIndex;
use Magento\Mtf\Constraint\AbstractAssertForm;
use Magento\User\Test\Fixture\User;

/**
 * Assert the latest entry for adminhtml_user_save action in admin action log report.
 *
 * Test Flow:
 * 1. Log in as default admin user
 * 2. Go to System -> Action Log -> Report
 * 3. Filter and find the latest entry for adminhtml_user_save action
 * 4. Open the report
 * 5. Perform assertions
 *
 * @security_private
 */
class AssertAdminLoggingLastUserSaveEntry extends AbstractAssertForm
{
    /**
     * Full action name
     *
     * @var string
     */
    private $fullActionName = 'adminhtml_user_save';

    /**
     * Assert that the latest entry for adminhtml_user_save action in admin action log report
     * does not contain current_password field and value.
     *
     * @param LoggingIndex $loggingIndex
     * @param Details $pageDetails
     * @param User $customAdmin
     */
    public function processAssert(
        LoggingIndex $loggingIndex,
        Details $pageDetails,
        User $customAdmin
    ) {
        $loggingIndex->open();
        $loggingIndex->getLogGridBlock()->searchSortAndOpen(['fullActionName' => $this->fullActionName]);

        \PHPUnit_Framework_Assert::assertEquals(
            $customAdmin->getUsername(),
            $pageDetails->getDetailsBlock()->getChangeAfterFieldValue('username'),
            'wrong username in admin action logging.'
        );

        \PHPUnit_Framework_Assert::assertFalse(
            $pageDetails->getDetailsBlock()->isChangeAfterFieldNameVisible('current_password'),
            'current_password name should not be in admin action logging.'
        );

        \PHPUnit_Framework_Assert::assertFalse(
            $pageDetails->getDetailsBlock()->isChangeAfterFieldValueVisible($customAdmin->getCurrentPassword()),
            'current_password value should not be in admin action logging.'
        );
    }

    /**
     * Successfully verify 'Value After Change' column in admin logging details.
     *
     * @return string
     */
    public function toString()
    {
        return "current_password name and value are not displayed in admin action logging.";
    }
}
