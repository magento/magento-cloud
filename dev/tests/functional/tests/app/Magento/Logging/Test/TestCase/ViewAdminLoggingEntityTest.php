<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Logging\Test\TestCase;

use Magento\User\Test\Fixture\User;
use Magento\Mtf\TestCase\Injectable;
use Magento\Backend\Test\Page\Adminhtml\SystemConfig;

/**
 * Steps:
 * 1. Log in as admin user.
 * 2. Go to Stores > Configuration.
 * 3. Click “Save Config” button.
 * 7. Perform all assertion.
 *
 * @group Admin_Logging
 * @ZephyrId MAGETWO-24702, MAGETWO-12411
 */
class ViewAdminLoggingEntityTest extends Injectable
{
    /* tags */
    const MVP = 'no';
    const SEVERITY = 'S2';
    /* end tags */

    /**
     * View Admin Logging details on backend.
     *
     * @param User $user
     * @param SystemConfig $systemConfig
     * @return void
     */
    public function testViewAdminLogging(User $user, SystemConfig $systemConfig)
    {
        // Precondition
        $user->persist();

        // Steps
        $this->objectManager->create(\Magento\User\Test\TestStep\LoginUserOnBackendStep::class, ['user' => $user])
            ->run();
        $systemConfig->open();
        $systemConfig->getPageActions()->save();
    }
}
