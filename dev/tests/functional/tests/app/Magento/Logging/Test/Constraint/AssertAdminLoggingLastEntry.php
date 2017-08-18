<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\Constraint;

use Magento\Logging\Test\Fixture\Logging;
use Magento\Logging\Test\Page\Adminhtml\Details;
use Magento\Logging\Test\Page\Adminhtml\Logging as LoggingIndex;
use Magento\Mtf\Constraint\AbstractAssertForm;
use Magento\User\Test\Fixture\User;

/**
 * Assert the latest entry in admin logging report for specified user.
 */
class AssertAdminLoggingLastEntry extends AbstractAssertForm
{
    /**
     * Assert that the latest entry in admin logging report for specified user
     * has a valid admin user data according his actions.
     *
     * @param User $user
     * @param Details $pageDetails
     * @param Logging $logging
     * @param LoggingIndex $loggingIndex
     */
    public function processAssert(User $user, Details $pageDetails, Logging $logging, LoggingIndex $loggingIndex)
    {
        // Prepare data
        $fixtureData = $logging->getData();
        $fixtureData['user'] = $user->getUsername();
        $fixtureData['user_id'] = $user->getUserId();

        // Verify logging detail data
        $loggingIndex->open();
        $loggingIndex->getLogGridBlock()->searchSortAndOpen(['username' => $user->getUsername()]);
        $formData = $pageDetails->getDetailsBlock()->getData();
        $diff = $this->verifyData($formData, $fixtureData);
        if (!$pageDetails->getDetailsBlock()->isLoggingDetailsGridVisible()) {
            $diff .= "\nLogging Details Grid is not present on page.";
        }
        \PHPUnit_Framework_Assert::assertEmpty($diff, $diff);
    }

    /**
     * Text success verify admin logging details.
     *
     * @return string
     */
    public function toString()
    {
        return "Displayed admin logging details equal to passed from fixture.";
    }
}
