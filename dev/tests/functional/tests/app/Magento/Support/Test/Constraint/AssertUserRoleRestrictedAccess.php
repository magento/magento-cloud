<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Support\Test\Constraint;

use Magento\Backend\Test\Page\Adminhtml\Dashboard;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\User\Test\Fixture\User;
use Magento\Support\Test\Page\Adminhtml\SupportBackupIndex;

/**
 * Asserts that user has only related permissions.
 */
class AssertUserRoleRestrictedAccess extends AbstractConstraint
{
    const DENIED_ACCESS = 'Access denied';

    /**
     * Asserts that user has only related permissions.
     *
     * @param BrowserInterface $browser
     * @param Dashboard $dashboard
     * @param User $user
     * @param SupportBackupIndex $backupIndexPage
     * @param string[] $restrictedAccess
     * @param string[] $denyUrls
     * @return void
     */
    public function processAssert(
        BrowserInterface $browser,
        Dashboard $dashboard,
        User $user,
        SupportBackupIndex $backupIndexPage,
        array $restrictedAccess,
        array $denyUrls
    ) {
        $this->objectManager->create(
            \Magento\User\Test\TestStep\LoginUserOnBackendStep::class,
            ['user' => $user]
        )->run();

        $menuItems = $dashboard->getMenuBlock()->getTopMenuItems();
        \PHPUnit_Framework_Assert::assertEquals($menuItems, $restrictedAccess, 'Wrong display menu.');

        foreach ($denyUrls as $denyUrl) {
            $browser->open($_ENV['app_backend_url'] . $denyUrl);
            $deniedMessage = $dashboard->getAccessDeniedBlock()->getTextFromAccessDeniedBlock();
            \PHPUnit_Framework_Assert::assertEquals(
                self::DENIED_ACCESS,
                $deniedMessage,
                'Possible access to denied page.'
            );
            \PHPUnit_Framework_Assert::assertFalse(
                $backupIndexPage->getMessagesBlock()->isVisible(),
                'Mass actions with restricted access should not show any message block'
            );
        }
    }

    /**
     * Returns success message if assert true.
     *
     * @return string
     */
    public function toString()
    {
        return 'Restricted admin user only had access to the allowed resource specified.';
    }
}
