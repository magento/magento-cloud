<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\Constraint;

use Magento\Logging\Test\Page\Adminhtml\Logging;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\User\Test\Fixture\User;

/**
 * Assert admin logging report entries.
 */
class AssertAdminLoggingGrid extends AbstractConstraint
{
    /**
     * Assert that admin logging report grid contains specified entries for the admin user.
     *
     * @param Logging $adminLoggingPage
     * @param User $user
     * @param array $actionLogs
     * @param User|null $userToLoggedIn [optional]
     */
    public function processAssert(
        Logging $adminLoggingPage,
        User $user,
        array $actionLogs,
        User $userToLoggedIn = null
    ) {
        // Login under specified admin user
        $userToLoggedIn = $userToLoggedIn !== null ? $userToLoggedIn : $user;
        $this->objectManager->create(
            \Magento\User\Test\TestStep\LoginUserOnBackendStep::class,
            ['user' => $userToLoggedIn]
        )->run();

        // Check admin logging entries in grid
        $adminLoggingPage->open();
        foreach ($actionLogs as $actionLog) {
            $actionLog['username'] = $user->getUsername();
            \PHPUnit_Framework_Assert::assertTrue(
                $adminLoggingPage->getLogGridBlock()->isRowVisible($actionLog),
                "There is no entry in Logging Report with data: \n" . print_r($actionLog, true)
            );
        }
    }

    /**
     * Text success verify admin logging details.
     *
     * @return string
     */
    public function toString()
    {
        return "All admin logging entries are present in grid for the admin user.";
    }
}
