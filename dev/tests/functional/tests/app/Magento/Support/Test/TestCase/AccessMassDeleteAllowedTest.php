<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Support\Test\TestCase;

use Magento\Mtf\TestCase\Scenario;

/**
 * Preconditions:
 * 1. Create a restricted admin with no access to support module.
 * 2. Log in as admin.
 * 3. Create a report.
 * 4. Verify that the report is created.
 * 5. Create a backup.
 * 6. Verify that the backup is created.
 * 7. Logout and login with the user admin.
 *
 * Steps:
 * 1. Login with the restricted admin user.
 * 2. Verify access to allowed modules.
 * 3. Verify denied access on mass delete for report and backup.
 * 4. Logout
 * 5. Login as admin
 * 6. Cleanup the created reports and backups
 *
 * @group Support_(PS)
 * @ZephyrId MAGETWO-61401
 */
class AccessMassDeleteAllowedTest extends Scenario
{
    /* tags */
    const MVP = 'no';
    const DOMAIN = 'PS';
    const SEVERITY = 'S1';
    /* end tags */

    public function test()
    {
        $this->executeScenario();
    }
}
